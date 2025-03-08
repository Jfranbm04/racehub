<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth')]
final class AuthController extends AbstractController
{
    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPassHash, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $user = new User();
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword($userPassHash->hashPassword($user, $data['password']));

            if (!isset($data['password'])) {
                return $this->json(['error' => 'Password is required'], Response::HTTP_BAD_REQUEST);
            }

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

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepo, UserPasswordHasherInterface $userPassHash): JsonResponse
    {
        if ($this->getUser()) {
            return $this->json([
                'error' => 'User is already logged in'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        $checkUser = $userRepo->findOneBy(['email' => $data['email']]);

        if (!isset($checkUser)) {
            return $this->json(['error' => 'User or password invalid'], Response::HTTP_UNAUTHORIZED);
        }

        if ($userPassHash->isPasswordValid($checkUser, trim($data['password']))) {
            return $this->json([
                'user' => $checkUser,
                'message' => 'Logged in successfully'
            ], Response::HTTP_OK, [], ['groups' => 'user:read']);
        }
        return $this->json(['error' => 'Something went wrong, if you see this message, contact support.'], Response::HTTP_I_AM_A_TEAPOT);
    }

    #[Route('/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {

        return $this->json([
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK);
    }
}
