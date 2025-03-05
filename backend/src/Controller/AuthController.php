<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/auth')]
final class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET'])]
    public function showRegister(): Response
    {
        return $this->render('main/register.html.twig');
    }

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $user = $serializer->deserialize($request->getContent(), User::class, 'json');
            $content = json_decode($request->getContent(), true);

            if (!isset($content['password'])) {
                return $this->json(['error' => 'Password is required'], Response::HTTP_BAD_REQUEST);
            }

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $content['password']));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], Response::HTTP_CREATED, [], ['groups' => 'user:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/login', name: 'app_login', methods: ['GET'])]
    public function showLogin(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('main/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
        ]);
    }

    #[Route('/login/submit', name: 'app_login_submit', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils): JsonResponse
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        
        if ($error) {
            return $this->json([
                'error' => $error->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->getUser();
        
        if (!$user) {
            return $this->json([
                'error' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user' => $user,
            'message' => 'Logged in successfully'
        ], Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        // The actual logout is handled by the security system
        return $this->json([
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK);
    }
}
