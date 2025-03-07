<?php

namespace App\Controller;

use App\Entity\RunningParticipant;
use App\Repository\RunningParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/running_participant')]
final class RunningParticipantController extends AbstractController
{
    #[Route('', name: 'app_running_participant_index', methods: ['GET'])]
    public function index(RunningParticipantRepository $runningParticipantRepository): JsonResponse
    {
        $participants = $runningParticipantRepository->findAll();
        return $this->json($participants, Response::HTTP_OK, [], [
            'groups' => [
                'running_participant:read',
                'user:read',
                'running:read'
            ],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }
    #[Route('/index_s', name: 'app_running_participant_index_s', methods: ['GET'])]
    public function index_s(RunningParticipantRepository $runningParticipantRepository): Response
    {
        return $this->render('running_participant/index.html.twig', [
            'running_participants' => $runningParticipantRepository->findAll(),
        ]);
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

            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->json(true, Response::HTTP_CREATED, [], [
                'groups' => [
                    'running_participant:read',
                    'user:read',
                    'running:read'
                ],
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_running_participant_show', methods: ['GET'])]
    public function show(int $id, RunningParticipantRepository $repository): JsonResponse
    {
        $participant = $repository->find($id);

        if (!$participant) {
            return $this->json(['error' => 'Participant not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($participant, Response::HTTP_OK, [], [
            'groups' => [
                'running_participant:read',
                'user:read',
                'running:read'
            ],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }

    #[Route('/{id}/edit', name: 'app_running_participant_edit', methods: ['PUT'])]
    public function edit(Request $request, RunningParticipant $participant, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (isset($data['user'])) {
                $user = $entityManager->getReference('App\Entity\User', $data['user']);
                $participant->setUser($user);
            }
            if (isset($data['running'])) {
                $running = $entityManager->getReference('App\Entity\Running', $data['running']);
                $participant->setRunning($running);
            }
            if (isset($data['dorsal'])) {
                $participant->setDorsal($data['dorsal']);
            }
            if (isset($data['banned'])) {
                $participant->setBanned($data['banned']);
            }

            $entityManager->flush();

            return $this->json($participant, Response::HTTP_OK, [], [
                'groups' => [
                    'running_participant:read',
                    'user:read',
                    'running:read'
                ],
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
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
