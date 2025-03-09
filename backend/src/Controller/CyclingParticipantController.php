<?php

namespace App\Controller;

use App\Entity\CyclingParticipant;
use App\Form\CyclingParticipantType;
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

    // index symfony
    #[Route('/index_s', name: 'app_cycling_participant_index_s', methods: ['GET'])]
    public function index_s(CyclingParticipantRepository $cyclingParticipantRepository): Response
    {
        return $this->render('cycling_participant/index.html.twig', [
            'cycling_participants' => $cyclingParticipantRepository->findAll(),
        ]);
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
            $dorsals = $cycling->getCyclingParticipants()->map(function ($participant) {
                return $participant->getDorsal();
            })->toArray();

            // This code is shit but fuck it we ball
            $dors = rand(1, $cycling->getAvailableSlots() * 2);
            for ($i = 0; $i < sizeof($dorsals); $i++) {
                if ($dors == $dorsals[$i]) {
                    $dors = rand(1, $cycling->getAvailableSlots() * 2);
                    $i = 0;
                }
                break;
            }
            $participant->setDorsal($dors);

            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->json($participant, Response::HTTP_CREATED, [], ['groups' => 'cycling_participant:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    // new Symfony
    #[Route('/new_s', name: 'app_cycling_participant_new_s', methods: ['GET', 'POST'])]
    public function new_s(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participant = new CyclingParticipant();
        $form = $this->createForm(CyclingParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set random dorsal
            $cycling = $participant->getCycling();
            $dorsals = $cycling->getCyclingParticipants()->map(function ($p) {
                return $p->getDorsal();
            })->toArray();

            $dors = rand(1, $cycling->getAvailableSlots() * 2);
            for ($i = 0; $i < sizeof($dorsals); $i++) {
                if ($dors == $dorsals[$i]) {
                    $dors = rand(1, $cycling->getAvailableSlots() * 2);
                    $i = 0;
                }
                break;
            }
            $participant->setDorsal($dors);

            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->redirectToRoute('app_cycling_participant_index_s');
        }

        return $this->render('cycling_participant/new.html.twig', [
            'cycling_participant' => $participant,
            'form' => $form,
        ]);
    }

    // edit symfony
    #[Route('/{id}/edit_s', name: 'app_cycling_participant_edit_s', methods: ['GET', 'POST'])]
    public function edit_s(Request $request, CyclingParticipant $participant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CyclingParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_cycling_participant_index_s');
        }

        return $this->render('cycling_participant/edit.html.twig', [
            'cycling_participant' => $participant,
            'form' => $form,
        ]);
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

            return $this->json(true, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
