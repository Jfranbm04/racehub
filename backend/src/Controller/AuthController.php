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

            return $this->render('main/login.html.twig', [
                'message' => 'Registration successful! Please login.'
            ]);
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

    #[Route('/login_s', name: 'api_login_s', methods: ['POST'])]
    public function login_s(Request $request, UserRepository $userRepo, UserPasswordHasherInterface $userPassHash): Response
    {
        // Verificar si el usuario ya está autenticado
        if ($this->getUser()) {
            return $this->redirectToRoute('app_indice'); // Redirige al índice si ya está logueado
        }

        // Obtener los datos enviados en la solicitud
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Validar que los datos requeridos estén presentes
        if (!$email || !$password) {
            return $this->render('main/login.html.twig', [
                'error' => 'Correo electrónico y contraseña son obligatorios.'
            ]);
        }

        // Buscar al usuario por su correo electrónico
        $checkUser = $userRepo->findOneBy(['email' => $email]);

        // Si no se encuentra el usuario o la contraseña es inválida
        if (!$checkUser || !$userPassHash->isPasswordValid($checkUser, trim($password))) {
            return $this->render('main/login.html.twig', [
                'error' => 'Correo electrónico o contraseña incorrectos.'
            ]);
        }

        // Si el inicio de sesión es exitoso, redirigir al índice
        return $this->redirectToRoute('app_indice');
    }

    #[Route('/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {

        return $this->json([
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK);
    }
}
