<?php

namespace App\Controller;

use App\Entity\CyclingParticipant;
use App\Repository\CyclingParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/cycling_participant')]
final class CyclingParticipantController extends AbstractController
{
    #[Route(name: 'app_cycling_participant_index', methods: ['GET'])]
    public function index(CyclingParticipantRepository $cyclingParticipantRepository): JsonResponse
    {
        $participants = $cyclingParticipantRepository->findAll();
        return $this->json($participants, Response::HTTP_OK, [], ['groups' => 'cycling_participant:read']);
    }

    #[Route('/new', name: 'app_cycling_participant_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Create new participant
            $participant = new CyclingParticipant();
            // Set the relationships
            $participant->setUser($entityManager->getReference('App\Entity\User', $data['user']));
            $cycling = $entityManager->getReference('App\Entity\Cycling', $data['cycling']);
            $participant->setCycling($cycling);

            //Set random dorsal


            /* for(int i = 0; i < nums.size(); i++){
                if(i == 90000){
                    GUI.launchMessage(2, "Nombre no disponible", "El nombre de usuario indicado no se\nencuentra disponible, inténtelo con uno distinto.");
                }
                if(ran.equals(nums.get(i))){
                    ran = String.valueOf(r.nextInt(99999));
                    i = 0;
                }
            } */

            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->json($participant, Response::HTTP_CREATED, [], ['groups' => 'cycling_participant:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_cycling_participant_show', methods: ['GET'])]
    public function show(CyclingParticipant $participant): JsonResponse
    {
        return $this->json($participant, Response::HTTP_OK, [], ['groups' => 'cycling_participant:read']);
    }

    #[Route('/{id}/edit', name: 'app_cycling_participant_edit', methods: ['PUT'])]
    public function edit(Request $request, CyclingParticipant $participant, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (isset($data['user'])) {
                $participant->setUser($entityManager->getReference('App\Entity\User', $data['user']));
            }
            if (isset($data['cycling'])) {

                $participant->setCycling($entityManager->getReference('App\Entity\Cycling', $data['cycling']));
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

            return $this->json($participant, Response::HTTP_OK, [], ['groups' => 'cycling_participant:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_cycling_participant_delete', methods: ['DELETE'])]
    public function delete(CyclingParticipant $participant, EntityManagerInterface $entityManager): JsonResponse
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
