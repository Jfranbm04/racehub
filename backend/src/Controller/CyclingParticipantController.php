<?php

namespace App\Controller;

use App\Entity\CyclingParticipant;
use App\Form\CyclingParticipantType;
use App\Repository\CyclingParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cycling/participant')]
final class CyclingParticipantController extends AbstractController
{
    #[Route(name: 'app_cycling_participant_index', methods: ['GET'])]
    public function index(CyclingParticipantRepository $cyclingParticipantRepository): Response
    {
        return $this->render('cycling_participant/index.html.twig', [
            'cycling_participants' => $cyclingParticipantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cycling_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cyclingParticipant = new CyclingParticipant();
        $form = $this->createForm(CyclingParticipantType::class, $cyclingParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cyclingParticipant);
            $entityManager->flush();

            return $this->redirectToRoute('app_cycling_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cycling_participant/new.html.twig', [
            'cycling_participant' => $cyclingParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cycling_participant_show', methods: ['GET'])]
    public function show(CyclingParticipant $cyclingParticipant): Response
    {
        return $this->render('cycling_participant/show.html.twig', [
            'cycling_participant' => $cyclingParticipant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cycling_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CyclingParticipant $cyclingParticipant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CyclingParticipantType::class, $cyclingParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cycling_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cycling_participant/edit.html.twig', [
            'cycling_participant' => $cyclingParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cycling_participant_delete', methods: ['POST'])]
    public function delete(Request $request, CyclingParticipant $cyclingParticipant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cyclingParticipant->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cyclingParticipant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cycling_participant_index', [], Response::HTTP_SEE_OTHER);
    }
}
