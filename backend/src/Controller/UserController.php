<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


#[Route('api/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepo, Request $request, EntityManagerInterface $entMngr): JsonResponse
    {
        try {
            $users = $userRepo->findAll();
            return $this->json($users, Response::HTTP_OK, [], ['groups' => 'user:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/new', name: 'app_user_edit', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entMngr): JsonResponse
    {
        try {
            $user = new User();
            $user->setName($request->get('name'));
            $user->setEmail($request->get('email'));
            $user->setPassword($request->get('password')); // TODO: Encriptar contraseña antes de persistirla

            $entMngr->persist($user);
            $entMngr->flush();

            return $this->json($user, Response::HTTP_CREATED, [], ['groups' => 'user:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        try {
            return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $entMngr): JsonResponse
    {
        try {
            $entMngr->remove($user);
            $entMngr->flush();
            return $this->json(['message' => 'Usuario eliminado'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['PUT'])]
    public function edit(User $user, Request $request, EntityManagerInterface $entMngr): JsonResponse
    {
        try {
            $user->setName($request->get('name'));
            $user->setEmail($request->get('email'));
            $user->setPassword($request->get('password')); // TODO: Encriptar contraseña antes de persistirla
            $user->setRoles([$request->get('role')]);
            $user->setBanned($request->get('banned'));

            $check = $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:new']);
            $entMngr->persist($user);
            $entMngr->flush();

            return $check;
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
