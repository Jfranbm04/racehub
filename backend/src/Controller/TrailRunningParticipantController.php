<?php

namespace App\Controller;

use App\Entity\TrailRunningParticipant;
use App\Repository\TrailRunningParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/trailrunning_participant')]
final class TrailRunningParticipantController extends AbstractController
{
    #[Route('', name: 'app_trail_running_participant_index', methods: ['GET'])]
    public function index(TrailRunningParticipantRepository $trailRunningParticipantRepository): JsonResponse
    {
        $participants = $trailRunningParticipantRepository->findAll();
        return $this->json($participants, Response::HTTP_OK, [], [
            'groups' => 'trail_running_participant:read',
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/new', name: 'app_trail_running_participant_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $participant = $serializer->deserialize($request->getContent(), TrailRunningParticipant::class, 'json');
            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->json($participant, Response::HTTP_CREATED, [], ['groups' => 'trail_running_participant:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    // #[Route('/{id}', name: 'app_trail_running_participant_show', methods: ['GET'])]
    // public function show(TrailRunningParticipant $participant): JsonResponse
    // {
    //     return $this->json($participant, Response::HTTP_OK, [], ['groups' => 'trail_running_participant:read']);
    // }
    #[Route('/{id}', name: 'app_trail_running_participant_show', methods: ['GET'])]
    public function show(int $id, TrailRunningParticipantRepository $repository): JsonResponse
    {
        $participant = $repository->find($id);

        if (!$participant) {
            return $this->json(['error' => 'Participant not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($participant, Response::HTTP_OK, [], [
            'groups' => [
                'trail_running_participant:read',
                'user_basic:read',
                'trail_running_basic:read'
            ],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trail_running_participant_edit', methods: ['PUT'])]
    public function edit(Request $request, TrailRunningParticipant $participant, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedParticipant = $serializer->deserialize($request->getContent(), TrailRunningParticipant::class, 'json', ['object_to_populate' => $participant]);
            $entityManager->flush();

            return $this->json($updatedParticipant, Response::HTTP_OK, [], ['groups' => 'trail_running_participant:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_trail_running_participant_delete', methods: ['DELETE'])]
    public function delete(TrailRunningParticipant $participant, EntityManagerInterface $entityManager): JsonResponse
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
