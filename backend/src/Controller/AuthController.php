<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\LoginAuthenticator;

#[Route('/auth')]
final class AuthController extends AbstractController
{
    #[Route('/login/submit', name: 'app_login', methods: ['POST'])]
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
