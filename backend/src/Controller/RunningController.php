<?php

namespace App\Controller;

use App\Entity\Running;
use App\Repository\RunningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/running')]
final class RunningController extends AbstractController
{

    #[Route(name: 'app_running_index', methods: ['GET'])]
    public function index(RunningRepository $runningRepository): JsonResponse
    {
        $runnings = $runningRepository->findAll();
        return $this->json($runnings, Response::HTTP_OK, [], ['groups' => 'running:read']);
    }
    // Muestra la lista de carreras
    #[Route('/index_s', name: 'app_running_index_s', methods: ['GET'])]
    public function index_s(EntityManagerInterface $entityManager, RunningRepository $runningRepository): Response
    {
        $runnings = $runningRepository->findAll();
        return $this->render('running/index.html.twig', [
            'runnings' => $runnings,
        ]);
    }

    #[Route('/{id}', name: 'app_running_show', methods: ['GET'])]
    public function show(Running $running): JsonResponse
    {
        return $this->json($running, Response::HTTP_OK, [], ['groups' => 'cycling:read']);
    }
    // Muestra la vista para crear una nueva carrera
    #[Route('/newView', name: 'app_running_start', methods: ['GET'])]
    public function start(): Response
    {
        return $this->render('running/new.html.twig');
    }

    #[Route('/new', name: 'app_running_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $running = $serializer->deserialize($request->getContent(), Running::class, 'json');
            $entityManager->persist($running);
            $entityManager->flush();

            return $this->json($running, Response::HTTP_CREATED, [], ['groups' => 'running:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    // Creo la funcion guardarcarrera
    #[Route('/guardarcarrera', name: 'app_running_guardar_carrera', methods: ['POST'])]
    public function guardarCarrera(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Crear una nueva instancia de Running
        $running = new Running();

        // Asignar valores desde el formulario
        $running->setName($request->request->get('name'));
        $running->setDescription($request->request->get('description'));
        $running->setDate(new \DateTime($request->request->get('date')));
        $running->setDistanceKm((int) $request->request->get('distance_km'));
        $running->setLocation($request->request->get('location'));
        $running->setCoordinates($request->request->get('coordinates'));
        $running->setEntryFee((int) $request->request->get('entry_fee'));
        $running->setAvailableSlots((int) $request->request->get('available_slots'));
        $running->setCategory($request->request->get('category'));
        $running->setImage($request->request->get('image'));
        $running->setStatus($request->request->get('status'));

        // Guardar la entidad en la base de datos
        $entityManager->persist($running);
        $entityManager->flush();

        // Redirigir al usuario a una página de confirmación o listado
        return $this->redirectToRoute('app_running_index');
    }



    #[Route('/{id}/status', name: 'app_running_status', methods: ['POST'])]
    public function updateStatus(Request $request, Running $running, EntityManagerInterface $entityManager): Response
    {
        $newStatus = $request->request->get('status');
        if (in_array($newStatus, ['open', 'closed', 'completed'])) {
            $running->setStatus($newStatus);
            $entityManager->flush();
            $this->addFlash('success', 'Status updated successfully');
        }

        return $this->redirectToRoute('app_running_index');
    }

    #[Route('/{id}/edit', name: 'app_running_edit', methods: ['PUT'])]
    public function edit(Request $request, Running $running, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedRunning = $serializer->deserialize($request->getContent(), Running::class, 'json', ['object_to_populate' => $running]);
            $entityManager->flush();

            return $this->json($updatedRunning, Response::HTTP_OK, [], ['groups' => 'running:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/edit_s', name: 'app_running_edit', methods: ['PUT'])]
    public function edit_s(Request $request, Running $running, EntityManagerInterface $entityManager): Response
    {
        $running->setName($request->request->get('name'));
        $running->setDescription($request->request->get('description'));
        $running->setDate(new \DateTime($request->request->get('date')));
        $running->setDistanceKm($request->request->get('distance_km'));
        $running->setLocation($request->request->get('location'));
        $running->setCoordinates($request->request->get('coordinates'));
        $running->setEntryFee($request->request->get('entry_fee'));
        $running->setAvailableSlots($request->request->get('available_slots'));
        $running->setStatus($request->request->get('status'));
        $running->setCategory($request->request->get('category'));
        $running->setImage($request->request->get('image'));

        $entityManager->persist($running);
        $entityManager->flush();

        return $this->redirectToRoute('app_running_show', ['id' => $running->getId()]);
    }



    #[Route('/{id}', name: 'app_running_delete', methods: ['DELETE'])]
    public function delete(Running $running, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($running);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    //-------METODOS SYMFONY----------

    #[Route('/new_s', name: 'app_running_start', methods: ['POST'])]
    public function new_s(Running $running, EntityManagerInterface $entityManager, Request $request): Response
    {
        $running = new Running();

        $running->setName($request->request->get('name'));
        $running->setDescription($request->request->get('description'));
        $running->setDate(new \DateTime($request->request->get('date')));
        $running->setDistanceKm($request->request->get('distance_km'));
        $running->setLocation($request->request->get('location'));
        $running->setCoordinates($request->request->get('coordinates'));
        $running->setEntryFee($request->request->get('entry_fee'));
        $running->setAvailableSlots($request->request->get('available_slots'));
        $running->setCategory($request->request->get('category'));
        $running->setImage($request->request->get('image'));
        $running->setStatus('open');

        $entityManager->persist($running);
        $entityManager->flush();

        return $this->render('main/index.html.twig');
    }


    #[Route('/{id}/edit_s', name: 'app_running_status', methods: ['PUT'])]
    public function running_s(Request $request, Running $running, EntityManagerInterface $entityManager): Response
    {
        $running->setName($request->request->get('name'));
        $running->setDescription($request->request->get('description'));
        $running->setDate(new \DateTime($request->request->get('date')));
        $running->setDistanceKm($request->request->get('distance_km'));
        $running->setLocation($request->request->get('location'));
        $running->setCoordinates($request->request->get('coordinates'));
        $running->setEntryFee($request->request->get('entry_fee'));
        $running->setAvailableSlots($request->request->get('available_slots'));
        $running->setCategory($request->request->get('category'));
        $running->setImage($request->request->get('image'));

        $entityManager->persist($running);
        $entityManager->flush();

        return $this->render('main/index.html.twig');
    }

    #[Route('/{id}', name: 'app_running_delete', methods: ['DELETE'])]
    public function delete_s(Running $running, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($running);
        $entityManager->flush();

        return $this->render('main/index.html.twig');
    }
}
