<?php

namespace App\Controller;

use App\Entity\Running;
use App\Form\RunningType;
use App\Repository\RunningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/running')]
final class RunningController extends AbstractController
{
    #[Route(name: 'app_running_index', methods: ['GET'])]
    public function index(RunningRepository $runningRepository): Response
    {
        return $this->render('running/index.html.twig', [
            'runnings' => $runningRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_running_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $running = new Running();
        $form = $this->createForm(RunningType::class, $running);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($running);
            $entityManager->flush();

            return $this->redirectToRoute('app_running_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('running/new.html.twig', [
            'running' => $running,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_running_show', methods: ['GET'])]
    public function show(Running $running): Response
    {
        return $this->render('running/show.html.twig', [
            'running' => $running,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_running_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Running $running, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RunningType::class, $running);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_running_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('running/edit.html.twig', [
            'running' => $running,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_running_delete', methods: ['POST'])]
    public function delete(Request $request, Running $running, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$running->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($running);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_running_index', [], Response::HTTP_SEE_OTHER);
    }
}
