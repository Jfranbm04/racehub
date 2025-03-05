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
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/auth')]
final class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPassHash, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try{
            $user = new User();
            $user -> setName($request -> get('name'));
            $user -> setEmail($request -> get('email'));
            $user -> setPassword($userPassHash -> hashPassword($user, $request -> get('password')));

            if($request -> get('password') == null){
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

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepo, UserPasswordHasherInterface $userPassHash): JsonResponse
    {
        if($this -> getUser()){
            return $this->json([
                'error' => 'User is already logged in'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $checkUser = $userRepo -> findOneBy(['email' => $request -> get('email')]);
        
        if(!isset($checkUser)){
            return $this -> json(['error' => 'User or password invalid'], Response::HTTP_UNAUTHORIZED);
        }

        if($userPassHash -> isPasswordValid($checkUser, trim($request -> get('password')) )){
            return $this->json([
                'user' => $checkUser,
                'message' => 'Logged in successfully'
            ], Response::HTTP_OK, [], []);
        }

        return $this -> json(['error' => 'Something went wrong, if you see this message, contact support.'], Response::HTTP_I_AM_A_TEAPOT);
    }

    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {

        return $this->json([
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK);
    }
}
