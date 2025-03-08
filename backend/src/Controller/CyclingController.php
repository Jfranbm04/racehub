<?php

namespace App\Controller;

use App\Entity\Cycling;
use App\Repository\CyclingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Form\CyclingType;

#[Route('api/cycling')]
final class CyclingController extends AbstractController
{


    // Index JSON
    #[Route(name: 'app_cycling_index', methods: ['GET'])]
    public function index(CyclingRepository $cyclRepo): JsonResponse
    {
        $cyclings = $cyclRepo->findAll();
        return $this->json($cyclings, Response::HTTP_OK, [], ['groups' => 'cycling:read']);
    }




    #[Route('/new', name: 'app_cycling_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cycling = new Cycling();
        $form = $this->createForm(CyclingType::class, $cycling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cycling->setStatus('open');
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
    public function show(Cycling $cycling): JsonResponse
    {
        return $this->json($cycling, Response::HTTP_OK, [], ['groups' => 'cycling:read']);
    }

    #[Route('/{id}/edit', name: 'app_cycling_edit', methods: ['PUT'])]
    public function edit(Request $request, Cycling $cycling, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedCycling = $serializer->deserialize($request->getContent(), Cycling::class, 'json', ['object_to_populate' => $cycling]);
            $entityManager->flush();

            return $this->json($updatedCycling, Response::HTTP_OK, [], ['groups' => 'cycling:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_cycling_delete', methods: ['DELETE'])]
    public function delete(Cycling $cycling, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($cycling);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    //-------METODOS SYMFONY----------

    #[Route('/index_s', name: 'app_cycling_index_s', methods: ['GET'])]
    public function index_s(CyclingRepository $cyclRepo, SerializerInterface $serializer): Response
    {
        $cyclings = $cyclRepo->findAll();
        return $this->render('cycling/index.html.twig', [
            'cyclings' => $cyclings
        ]);
    }



    #[Route('/{id}', name: 'app_cycling_show', methods: ['GET'])]
    public function show_s(Cycling $cycling): Response
    {
        return $this->render('cycling/show.html.twig', [
            'cycling' => $cycling,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cycling_edit', methods: ['GET', 'POST'])]
    public function edit_s(Request $request, Cycling $cycling, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CyclingType::class, $cycling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_cycling_index');
        }

        return $this->render('cycling/edit.html.twig', [
            'form' => $form->createView(),
            'cycling' => $cycling,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_cycling_delete', methods: ['POST'])]
    public function delete_s(Request $request, Cycling $cycling, EntityManagerInterface $entityManager): Response
    {
        // Eliminar todos los participantes asociados a esta carrera
        foreach ($cycling->getCyclingParticipants() as $participant) {
            $entityManager->remove($participant);
        }

        // Ahora puedes eliminar la carrera
        $entityManager->remove($cycling);
        $entityManager->flush();

        return $this->redirectToRoute('app_cycling_index_s');
    }
}
