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
}
