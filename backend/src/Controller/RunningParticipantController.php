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
            $running = $entityManager->getReference('App\Entity\Running', $data['running']);

            // Set the relationships
            $participant->setUser($entityManager->getReference('App\Entity\User', $data['user']));
            $participant->setRunning($running);

            //Set random dorsal
            $dorsals = $running->getrunningParticipants()->map(function ($participant) {
                return $participant->getDorsal();
            })->toArray();

            // This code is shit but fuck it we ball
            $dors = rand(1, $running->getAvailableSlots() * 2);
            for ($i = 0; $i < sizeof($dorsals); $i++) {
                if ($dors == $dorsals[$i]) {
                    $dors = rand(1, $running->getAvailableSlots() * 2);
                    $i = 0;
                }
                break;
            }
            $participant->setDorsal($dors);

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

    // New symfony
    #[Route('/new_s', name: 'app_running_participant_new_s', methods: ['GET', 'POST'])]
    public function new_s(Request $request, EntityManagerInterface $entityManager): Response
    {
        $runningParticipant = new RunningParticipant();

        $form = $this->createForm(RunningParticipantType::class, $runningParticipant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($runningParticipant);
                $entityManager->flush();

                return $this->redirectToRoute('app_running_participant_index_s');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error al crear el participante: ' . $e->getMessage());
            }
        }

        // Renderizar la vista del formulario
        return $this->render('running_participant/new.html.twig', [
            'running_participant' => $runningParticipant,
            'form' => $form->createView(),
        ]);
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

    #[Route('/{id}/edit', name: 'app_running_participant_edit', methods: ['GET', 'POST'])]
    public function edit_s(Request $request, RunningParticipant $runningParticipant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RunningParticipantType::class, $runningParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_running_participant_index_s');
        }

        return $this->render('running_participant/edit.html.twig', [
            'running_participant' => $runningParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_running_participant_delete', methods: ['DELETE'])]
    public function delete(RunningParticipant $participant, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($participant);
            $entityManager->flush();

            return $this->json(true, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
