<?php

namespace App\Controller;

use App\Entity\TrailRunning;
use App\Form\TrailRunningType;
use App\Repository\TrailRunningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/trailrunning')]
final class TrailRunningController extends AbstractController
{
    #[Route(name: 'app_trail_running_index', methods: ['GET'])]
    public function index(TrailRunningRepository $trailRunningRepository): Response
    {
        return $this->render('trail_running/index.html.twig', [
            'trail_runnings' => $trailRunningRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_trail_running_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trailRunning = new TrailRunning();
        $form = $this->createForm(TrailRunningType::class, $trailRunning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trailRunning);
            $entityManager->flush();

            return $this->redirectToRoute('app_trail_running_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trail_running/new.html.twig', [
            'trail_running' => $trailRunning,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trail_running_show', methods: ['GET'])]
    public function show(TrailRunning $trailRunning): Response
    {
        return $this->render('trail_running/show.html.twig', [
            'trail_running' => $trailRunning,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trail_running_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrailRunning $trailRunning, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrailRunningType::class, $trailRunning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trail_running_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trail_running/edit.html.twig', [
            'trail_running' => $trailRunning,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trail_running_delete', methods: ['POST'])]
    public function delete(Request $request, TrailRunning $trailRunning, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trailRunning->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($trailRunning);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trail_running_index', [], Response::HTTP_SEE_OTHER);
    }
}
