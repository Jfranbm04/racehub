<?php

namespace App\Controller;

use App\Entity\TrailRunningParticipant;
use App\Form\TrailRunningParticipantType;
use App\Repository\TrailRunningParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/trailrunning/participant')]
final class TrailRunningParticipantController extends AbstractController
{
    #[Route(name: 'app_trail_running_participant_index', methods: ['GET'])]
    public function index(TrailRunningParticipantRepository $trailRunningParticipantRepository): Response
    {
        return $this->render('trail_running_participant/index.html.twig', [
            'trail_running_participants' => $trailRunningParticipantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_trail_running_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trailRunningParticipant = new TrailRunningParticipant();
        $form = $this->createForm(TrailRunningParticipantType::class, $trailRunningParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trailRunningParticipant);
            $entityManager->flush();

            return $this->redirectToRoute('app_trail_running_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trail_running_participant/new.html.twig', [
            'trail_running_participant' => $trailRunningParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trail_running_participant_show', methods: ['GET'])]
    public function show(TrailRunningParticipant $trailRunningParticipant): Response
    {
        return $this->render('trail_running_participant/show.html.twig', [
            'trail_running_participant' => $trailRunningParticipant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trail_running_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrailRunningParticipant $trailRunningParticipant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrailRunningParticipantType::class, $trailRunningParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trail_running_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trail_running_participant/edit.html.twig', [
            'trail_running_participant' => $trailRunningParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trail_running_participant_delete', methods: ['POST'])]
    public function delete(Request $request, TrailRunningParticipant $trailRunningParticipant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trailRunningParticipant->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($trailRunningParticipant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trail_running_participant_index', [], Response::HTTP_SEE_OTHER);
    }
}
