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

    #[Route('/new', name: 'app_trail_running_participant_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Create new participant
            $participant = new TrailRunningParticipant();

            // Get existing entities
            $trailRunning = $entityManager->getReference('App\Entity\TrailRunning', $data['trailRunning']);

            // Set the relationships
            $participant->setUser($entityManager->getReference('App\Entity\User', $data['user']));
            $participant->setTrailRunning($trailRunning);

            //Set random dorsal
            $dorsals = $trailRunning -> gettrailRunningParticipants() -> map(function ($participant){
                return $participant -> getDorsal();
            }) -> toArray();

            // This code is shit but fuck it we ball
            $dors = rand(1, $trailRunning -> getAvailableSlots() * 2);
            for($i = 0; $i < sizeof($dorsals); $i++){
                if($dors == $dorsals[$i]){
                    $dors = rand(1, $trailRunning -> getAvailableSlots() * 2);
                    $i = 0;
                }
                break;
            }
            $participant -> setDorsal($dors);

            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->json($participant, Response::HTTP_CREATED, [], ['groups' => 'trail_running_participant:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

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
    public function edit(Request $request, TrailRunningParticipant $participant, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (isset($data['user'])) {
                $user = $entityManager->getReference('App\Entity\User', $data['user']);
                $participant->setUser($user);
            }
            if (isset($data['trailRunning'])) {
                $trailRunning = $entityManager->getReference('App\Entity\TrailRunning', $data['trailRunning']);
                $participant->setTrailRunning($trailRunning);
            }
            if (isset($data['time'])) {
                $participant->setBanned($data['time']);
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
                    'trail_running_participant:read',
                    'user_basic:read',
                    'trail_running_basic:read'
                ],
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
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

            return $this->json(true, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
