<?php

namespace App\Controller;

use App\Entity\Cycling;
use App\Form\CyclingType;
use App\Repository\CyclingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cycling')]
final class CyclingController extends AbstractController
{
    #[Route(name: 'app_cycling_index', methods: ['GET'])]
    public function index(CyclingRepository $cyclingRepository): Response
    {
        return $this->render('cycling/index.html.twig', [
            'cyclings' => $cyclingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cycling_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cycling = new Cycling();
        $form = $this->createForm(CyclingType::class, $cycling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cycling);
            $entityManager->flush();

            return $this->redirectToRoute('app_cycling_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cycling/new.html.twig', [
            'cycling' => $cycling,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cycling_show', methods: ['GET'])]
    public function show(Cycling $cycling): Response
    {
        return $this->render('cycling/show.html.twig', [
            'cycling' => $cycling,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cycling_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cycling $cycling, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CyclingType::class, $cycling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cycling_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cycling/edit.html.twig', [
            'cycling' => $cycling,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cycling_delete', methods: ['POST'])]
    public function delete(Request $request, Cycling $cycling, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cycling->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cycling);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cycling_index', [], Response::HTTP_SEE_OTHER);
    }
}
