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
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->json($users, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('/new', name: 'app_user_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $user = $serializer->deserialize($request->getContent(), User::class, 'json');
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json($user, Response::HTTP_CREATED, [], ['groups' => 'user:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['PUT'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedUser = $serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);
            $entityManager->flush();

            return $this->json($updatedUser, Response::HTTP_OK, [], ['groups' => 'user:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
