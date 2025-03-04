<?php

namespace App\Controller;

use App\Entity\Running;
use App\Repository\RunningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/running')]
final class RunningController extends AbstractController
{
    #[Route(name: 'app_running_index', methods: ['GET'])]
    public function index(RunningRepository $runningRepository): JsonResponse
    {
        $runnings = $runningRepository->findAll();
        return $this->json($runnings, Response::HTTP_OK, [], ['groups' => 'running:read']);
    }

    #[Route('/new', name: 'app_running_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $running = $serializer->deserialize($request->getContent(), Running::class, 'json');
            $entityManager->persist($running);
            $entityManager->flush();

            return $this->json($running, Response::HTTP_CREATED, [], ['groups' => 'running:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_running_show', methods: ['GET'])]
    public function show(Running $running): JsonResponse
    {
        return $this->json($running, Response::HTTP_OK, [], ['groups' => 'running:read']);
    }

    #[Route('/{id}/edit', name: 'app_running_edit', methods: ['PUT'])]
    public function edit(Request $request, Running $running, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedRunning = $serializer->deserialize($request->getContent(), Running::class, 'json', ['object_to_populate' => $running]);
            $entityManager->flush();

            return $this->json($updatedRunning, Response::HTTP_OK, [], ['groups' => 'running:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_running_delete', methods: ['DELETE'])]
    public function delete(Running $running, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($running);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
