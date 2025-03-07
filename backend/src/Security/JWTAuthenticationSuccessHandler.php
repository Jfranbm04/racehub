<?php

namespace App\Security;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class JWTAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $jwtManager;
    private $serializer;
    private $eventDispatcher;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        SerializerInterface $serializer,
        $eventDispatcher = null
    ) {
        $this->jwtManager = $jwtManager;
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?\Symfony\Component\HttpFoundation\Response
    {
        $user = $token->getUser();
        
        // Check if user is banned
        if ($user instanceof User && $user->getBanned()) {
            return new JsonResponse(
                ['error' => 'Your account has been banned. Please contact the administrator.'],
                Response::HTTP_FORBIDDEN
            );
        }
        
        $jwt = $this->jwtManager->create($user);

        // Normalize user data with groups
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['user:read'])
            ->toArray();
        $userData = $this->serializer->serialize($user, 'json', $context);
        $userData = json_decode($userData, true);

        $data = [
            'token' => $jwt,
            'user' => $userData
        ];

        $response = new JWTAuthenticationSuccessResponse($jwt);
        $response->setData($data);

        $event = new AuthenticationSuccessEvent(
            $data,
            $user,
            $response
        );

        if (null !== $this->eventDispatcher) {
            $this->eventDispatcher->dispatch($event, 'lexik_jwt_authentication.on_authentication_success');
        }

        return $event->getResponse();
    }
}
