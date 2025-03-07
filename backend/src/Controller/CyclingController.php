<?php

namespace App\Controller;

use App\Entity\Cycling;
use App\Form\CyclingType;
use App\Repository\CyclingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/cycling')]
final class CyclingController extends AbstractController
{
    #[Route(name: 'app_cycling_index', methods: ['GET'])]
    public function index(CyclingRepository $cyclRepo, SerializerInterface $serializer): JsonResponse
    {
        $cyclings = $cyclRepo->findAll();
        return $this->json($cyclings, Response::HTTP_OK, [], ['groups' => 'cycling:read']);
    }

    #[Route('/new', name: 'app_cycling_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $cycling = $serializer->deserialize($request->getContent(), Cycling::class, 'json');
            $entityManager->persist($cycling);
            $entityManager->flush();

            return $this->json($cycling, Response::HTTP_CREATED, [], ['groups' => 'cycling:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
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
    public function delete(Request $request, Cycling $cycling, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($cycling);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/new_s', name: 'app_cycling_start', methods: ['POST'])]
    public function new_s(Cycling $cycling, EntityManagerInterface $entityManager): Response
    {
        $cycling = new Cycling();

        $cycling->setName('Cycling 1');
        $cycling->setDescription('Cycling 1 description');
        $cycling->setDate(new \DateTime());
        $cycling->setDistanceKm(0);
        $cycling->setLocation('Cycling 1 location');
        $cycling->setCoordinates('0,0');
        $cycling->setUnevenness(0);
        $cycling->setEntryFee(0);
        $cycling->setAvailableSlots(0);  
        $cycling->setStatus(0);
        $cycling->setCategory('Cycling 1 category');      
        $cycling->setStatus('In progress');
        $cycling->setImage('Cycling 1 image url');

        $entityManager->persist($cycling);
        $entityManager->flush();

        return $this->render('cycling/show.html.twig', [
            'cycling' => $cycling,
        ]);
    }

    #[Route('/{id}/edit_s', name: 'app_cycling_edit', methods: ['PUT'])]
    public function edit_s(Request $request, Cycling $cycling, EntityManagerInterface $entityManager): Response
    {
        $cycling->setName($request->request->get('name'));
        $cycling->setDescription($request->request->get('description'));
        $cycling->setDate(new \DateTime($request->request->get('date')));
        $cycling->setDistanceKm($request->request->get('distance_km'));
        $cycling->setLocation($request->request->get('location'));
        $cycling->setCoordinates($request->request->get('coordinates'));
        $cycling->setUnevenness($request->request->get('unevenness'));
        $cycling->setEntryFee($request->request->get('entry_fee'));
        $cycling->setAvailableSlots($request->request->get('available_slots'));
        $cycling->setStatus($request->request->get('status'));
        $cycling->setCategory($request->request->get('category'));
        $cycling->setImage($request->request->get('image'));

        $entityManager->persist($cycling);
        $entityManager->flush();

        return $this->redirectToRoute('app_cycling_show', ['id' => $cycling->getId()]);
    }

    #[Route('/{id}', name: 'app_cycling_delete', methods: ['DELETE'])]
    public function delete_s(Cycling $cycling, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($cycling);
        $entityManager->flush();

        return $this->redirectToRoute('app_cycling_index');
    }

}
