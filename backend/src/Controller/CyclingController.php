<?php

namespace App\Controller;

use App\Entity\Cycling;
use App\Form\CyclingType;
use App\Repository\CyclingRepository;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

    //-------METODOS SYMFONY----------

    #[Route('', name: 'app_cycling_index', methods: ['GET'])]
    public function index_s(CyclingRepository $cyclingRepository): Response
    {
        return $this->render('cycling/index.html.twig', [
            'cyclings' => $cyclingRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_cycling_show', methods: ['GET'])]
    public function show_s(Cycling $cycling): Response
    {
        return $this->render('cycling/show.html.twig', [
            'cycling' => $cycling,
        ]);
    }

    #[Route('/new_s', name: 'app_cycling_start', methods: ['POST'])]
    public function new_s(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cycling = new Cycling();

        // Capturando los datos enviados por el formulario
        $cycling->setName($request->request->get('name'));
        $cycling->setDescription($request->request->get('description'));
        $cycling->setDate(new \DateTime($request->request->get('date')));
        $cycling->setDistanceKm((float)$request->request->get('distance_km'));
        $cycling->setLocation($request->request->get('location'));
        $cycling->setCoordinates($request->request->get('coordinates'));
        $cycling->setUnevenness((int)$request->request->get('unevenness'));
        $cycling->setEntryFee((float)$request->request->get('entry_fee'));
        $cycling->setAvailableSlots((int)$request->request->get('available_slots'));
        $cycling->setStatus($request->request->get('status'));
        $cycling->setCategory($request->request->get('category'));
        $cycling->setImage($request->request->get('image'));

        // Guardando en la base de datos
        $entityManager->persist($cycling);
        $entityManager->flush();

        return $this->render('cycling/show.html.twig', [
            'cycling' => $cycling,
        ]);
    }


    
    // #[Route('/{id}/edit_s', name: 'app_cycling_edit', methods: ['GET', 'PUT'])]
    // public function edit_s(Request $request, Cycling $cycling, EntityManagerInterface $entityManager): Response
    // {
    //     // Crear el formulario de edición
    //     $form = $this->createForm(CyclingType::class, $cycling);

    //     // Si es una solicitud GET, mostrar el formulario con los datos actuales
    //     if ($request->isMethod('GET')) {
    //         return $this->render('cycling/edit.html.twig', [
    //             'form' => $form->createView(),
    //             'cycling' => $cycling,  // Los datos actuales del ciclo
    //         ]);
    //     }

    //     // Si es una solicitud PUT, manejar la actualización
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Guardar los cambios en la base de datos
    //         $entityManager->persist($cycling);
    //         $entityManager->flush();

    //         // Redirigir al usuario a otra página después de la actualización
    //         return $this->redirectToRoute('app_admin'); // O la ruta que prefieras
    //     }

    //     // Si el formulario no es válido, vuelve a mostrarlo con los errores
    //     return $this->render('cycling/edit.html.twig', [
    //         'form' => $form->createView(),
    //         'cycling' => $cycling,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_cycling_delete', methods: ['DELETE'])]
    public function delete_s(Cycling $cycling, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($cycling);
        $entityManager->flush();

        return $this->redirectToRoute('app_cycling_index');
    }
}
