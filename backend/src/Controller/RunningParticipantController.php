<?php

namespace App\Controller;

use App\Entity\RunningParticipant;
use App\Form\RunningParticipantType;
use App\Repository\RunningParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/running_participant')]
final class RunningParticipantController extends AbstractController
{
    #[Route(name: 'app_running_participant_index', methods: ['GET'])]
    public function index(RunningParticipantRepository $runningParticipantRepository): JsonResponse
    {
        $participants = $runningParticipantRepository->findAll();
        return $this->json($participants, Response::HTTP_OK, [], ['groups' => 'running_participant:read']);
    }

    #[Route('/new', name: 'app_running_participant_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $participant = $serializer->deserialize($request->getContent(), RunningParticipant::class, 'json');
            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->json($participant, Response::HTTP_CREATED, [], ['groups' => 'running_participant:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_running_participant_show', methods: ['GET'])]
    public function show(RunningParticipant $participant): JsonResponse
    {
        return $this->json($participant, Response::HTTP_OK, [], ['groups' => 'running_participant:read']);
    }

    #[Route('/{id}/edit', name: 'app_running_participant_edit', methods: ['PUT'])]
    public function edit(Request $request, RunningParticipant $participant, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedParticipant = $serializer->deserialize($request->getContent(), RunningParticipant::class, 'json', ['object_to_populate' => $participant]);
            $entityManager->flush();

            return $this->json($updatedParticipant, Response::HTTP_OK, [], ['groups' => 'running_participant:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_running_participant_delete', methods: ['DELETE'])]
    public function delete(RunningParticipant $participant, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($participant);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
