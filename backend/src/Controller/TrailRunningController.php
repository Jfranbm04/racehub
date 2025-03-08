<?php

namespace App\Controller;

use App\Entity\TrailRunning;
use App\Repository\TrailRunningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/trailrunning')]
final class TrailRunningController extends AbstractController
{
    #[Route(name: 'app_trail_running_index', methods: ['GET'])]
    public function index(TrailRunningRepository $trailRunningRepository): JsonResponse
    {
        $trailRunnings = $trailRunningRepository->findAll();
        return $this->json($trailRunnings, Response::HTTP_OK, [], ['groups' => 'trail_running:read']);
    }

    #[Route('/new', name: 'app_trail_running_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $trailRunning = $serializer->deserialize($request->getContent(), TrailRunning::class, 'json');
            $entityManager->persist($trailRunning);
            $entityManager->flush();

            return $this->json($trailRunning, Response::HTTP_CREATED, [], ['groups' => 'trail_running:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_trail_running_show', methods: ['GET'])]
    public function show(TrailRunning $trailRunning): JsonResponse
    {
        return $this->json($trailRunning, Response::HTTP_OK, [], ['groups' => 'trail_running:read']);
    }

    #[Route('/{id}/edit', name: 'app_trail_running_edit', methods: ['PUT'])]
    public function edit(Request $request, TrailRunning $trailRunning, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedTrailRunning = $serializer->deserialize($request->getContent(), TrailRunning::class, 'json', ['object_to_populate' => $trailRunning]);
            $entityManager->flush();

            return $this->json($updatedTrailRunning, Response::HTTP_OK, [], ['groups' => 'trail_running:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_trail_running_delete', methods: ['DELETE'])]
    public function delete(TrailRunning $trailRunning, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($trailRunning);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
