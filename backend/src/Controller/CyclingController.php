<?php

namespace App\Controller;

use App\Entity\Cycling;
use App\Form\CyclingType;
use App\Repository\CyclingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/cycling')]
final class CyclingController extends AbstractController
{
    #[Route(name: 'app_cycling_index', methods: ['GET'])]
    public function index(CyclingRepository $cyclingRepository, SerializerInterface $serializer): JsonResponse
    {
        $cyclings = $cyclingRepository->findAll();
        return $this->json($cyclings, Response::HTTP_OK, [], ['groups' => 'cycling:read']);
    }

    #[Route('/new', name: 'app_cycling_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $cycling = $serializer->deserialize($request->getContent(), Cycling::class, 'json');
            $entityManager->persist($cycling);
            $entityManager->flush();

            return $this->json($cycling, Response::HTTP_CREATED, [], ['groups' => 'cycling:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_cycling_show', methods: ['GET'])]
    public function show(Cycling $cycling): JsonResponse
    {
        return $this->json($cycling, Response::HTTP_OK, [], ['groups' => 'cycling:read']);
    }

    #[Route('/{id}/edit', name: 'app_cycling_edit', methods: ['PUT'])]
    public function edit(Request $request, Cycling $cycling, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedCycling = $serializer->deserialize($request->getContent(), Cycling::class, 'json', ['object_to_populate' => $cycling]);
            $entityManager->flush();

            return $this->json($updatedCycling, Response::HTTP_OK, [], ['groups' => 'cycling:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_cycling_delete', methods: ['DELETE'])]
    public function delete(Request $request, Cycling $cycling, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($cycling);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
