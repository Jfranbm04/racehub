<?php

namespace App\Controller;

use App\Entity\CyclingParticipant;
use App\Entity\Running;
use App\Entity\RunningParticipant;
use App\Repository\CyclingParticipantRepository;
use App\Repository\RunningParticipantRepository;
use App\Repository\RunningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/running_participant')]
final class RunningParticipantController extends AbstractController
{

    #[Route(name: 'app_running_index', methods: ['GET'])]
    public function index(RunningRepository $runningRepository): JsonResponse
    {
        $runnings = $runningRepository->findAll();
        return $this->json($runnings, Response::HTTP_OK, [], ['groups' => 'running:read']);
    }

    #[Route('/new', name: 'app_running_participant_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Create new participant
            $participant = new RunningParticipant();

            // Get existing entities
            $user = $entityManager->getReference('App\Entity\User', $data['user']);
            $running = $entityManager->getReference('App\Entity\Running', $data['running']);

            // Set the relationships
            $participant->setUser($user);
            $participant->setRunning($running);
            $participant->setDorsal($data['dorsal']);
            $participant->setBanned($data['banned']);

            if (isset($data['time'])) {
                $participant->setTime(new \DateTime($data['time']));
            }

            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->json($participant, Response::HTTP_CREATED, [], ['groups' => 'running_participant:read']);
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
