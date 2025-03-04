<?php

namespace App\Controller;

use App\Entity\RunningParticipant;
use App\Form\RunningParticipantType;
use App\Repository\RunningParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/running/participant')]
final class RunningParticipantController extends AbstractController
{
    #[Route(name: 'app_running_participant_index', methods: ['GET'])]
    public function index(RunningParticipantRepository $runningParticipantRepository): Response
    {
        return $this->render('running_participant/index.html.twig', [
            'running_participants' => $runningParticipantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_running_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $runningParticipant = new RunningParticipant();
        $form = $this->createForm(RunningParticipantType::class, $runningParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($runningParticipant);
            $entityManager->flush();

            return $this->redirectToRoute('app_running_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('running_participant/new.html.twig', [
            'running_participant' => $runningParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_running_participant_show', methods: ['GET'])]
    public function show(RunningParticipant $runningParticipant): Response
    {
        return $this->render('running_participant/show.html.twig', [
            'running_participant' => $runningParticipant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_running_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RunningParticipant $runningParticipant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RunningParticipantType::class, $runningParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_running_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('running_participant/edit.html.twig', [
            'running_participant' => $runningParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_running_participant_delete', methods: ['POST'])]
    public function delete(Request $request, RunningParticipant $runningParticipant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$runningParticipant->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($runningParticipant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_running_participant_index', [], Response::HTTP_SEE_OTHER);
    }
}
