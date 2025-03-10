<?php

namespace App\Controller;

use App\Entity\TrailRunning;
use App\Form\TrailRunningType;
use App\Repository\TrailRunningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

#[Route('api/trailrunning')]
final class TrailRunningController extends AbstractController
{
    #[Route(name: 'app_trail_running_index', methods: ['GET'])]
    public function index(TrailRunningRepository $trailRunningRepository): Response
    {
        $trailRunnings = $trailRunningRepository->findAll();

        return $this->json($trailRunnings, Response::HTTP_OK, [], ['groups' => 'trail_running:read']);
    }
    // Index symfony
    #[Route('/index_s', name: 'app_trail_running_index_s', methods: ['GET'])]
    public function index_s(EntityManagerInterface $entityManager, TrailRunningRepository $trailRunningRepository): Response
    {
        $trailRunnings = $trailRunningRepository->findAll();
        return $this->render('trail_running/index.html.twig', [
            'trail_runnings' => $trailRunnings,
        ]);
    }
    #[Route('/new', name: 'app_trailrunning_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trailRunning = new TrailRunning();
        $form = $this->createForm(TrailRunningType::class, $trailRunning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trailRunning);
            $entityManager->flush();

            return $this->redirectToRoute('app_trail_running_index_s');
        }

        return $this->render('trail_running/new.html.twig', [
            'trail_running' => $trailRunning,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_trail_running_show', methods: ['GET'])]
    public function show(TrailRunning $trailRunning): JsonResponse
    {
        return $this->json($trailRunning, Response::HTTP_OK, [], ['groups' => 'trail_running:read']);
    }

    #[Route('/{id}/edit', name: 'app_trail_running_edit', methods: ['PUT'])]
    public function edit(Request $request, TrailRunning $trailRunning, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedTrailRunning = $serializer->deserialize($request->getContent(), TrailRunning::class, 'json', ['object_to_populate' => $trailRunning]);
            $entityManager->flush();

            return $this->json($updatedTrailRunning, Response::HTTP_OK, [], ['groups' => 'trail_running:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_trail_running_delete', methods: ['DELETE'])]
    public function delete(TrailRunning $trailRunning, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($trailRunning);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_trail_running_participant', methods: ['GET'])]
    public function participant_s(int $id, TrailRunningRepository $trailRunningRepository): Response
    {
        $participant = $trailRunningRepository->find($id);

        if (!$participant) {
            throw $this->createNotFoundException('No participant found for id ' . $id);
        }

        return $this->render('trail_running/participant.html.twig', [
            'participant' => $participant,
        ]);
    }

    //-------METODOS SYMFONY----------

    #[Route('/new', name: 'app_trail_running_new', methods: ['POST'])]
    public function new_s(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trailRunning = new TrailRunning();
        $trailRunning->setName($request->request->get('name'));
        $trailRunning->setDescription($request->request->get('description'));
        $trailRunning->setDate(new \DateTime($request->request->get('date')));
        $trailRunning->setDistanceKm($request->request->get('distance'));
        $trailRunning->setLocation($request->request->get('location'));
        $trailRunning->setCoordinates($request->request->get('coordinates'));
        $trailRunning->setUnevenness($request->request->get('unevenness'));
        $trailRunning->setEntryFee($request->request->get('entry_fee'));
        $trailRunning->setAvailableSlots($request->request->get('available_slots'));
        $trailRunning->setStatus($request->request->get('status'));
        $trailRunning->setCategory($request->request->get('category'));
        $trailRunning->setImage($request->request->get('image'));

        $entityManager->persist($trailRunning);
        $entityManager->flush();

        return $this->render('trail_running/show.html.twig', [
            'trailRunning' => $trailRunning,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trail_running_edit', methods: ['GET', 'POST'])]
    public function edit_s(Request $request, TrailRunning $trailRunning, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrailRunningType::class, $trailRunning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_trail_running_index_s');
        }

        return $this->render('trail_running/edit.html.twig', [
            'trail_running' => $trailRunning,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/delete', name: 'app_trail_running_delete', methods: ['POST'])]
    public function delete_s(Request $request, TrailRunning $trailRunning, EntityManagerInterface $entityManager): Response
    {
        // Eliminar todos los participantes asociados a esta carrera
        foreach ($trailRunning->getTrailRunningParticipants() as $participant) {
            $entityManager->remove($participant);
        }

        // Ahora puedes eliminar la carrera
        $entityManager->remove($trailRunning);
        $entityManager->flush();

        return $this->redirectToRoute('app_trail_running_index_s');
    }
}
