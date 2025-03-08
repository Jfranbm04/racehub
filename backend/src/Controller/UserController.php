<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('api/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepo, Request $request, EntityManagerInterface $entMngr): JsonResponse
    {
        try {
            $users = $userRepo->findAll();
            return $this->json($users, Response::HTTP_OK, [], ['groups' => 'user:read']);
        }
        catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/new', name: 'app_user_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entMngr, UserPasswordHasherInterface $userPassHash): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $user = new User();

            $user -> setName($data['name']);
            $user -> setEmail($data['email']);
            $user -> setPassword($userPassHash -> hashPassword($user, $data['password']));

            $entMngr->persist($user);
            $entMngr->flush();

            return $this->json($user, Response::HTTP_CREATED, [], ['groups' => 'user:read']);
        }
        catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET', 'DELETE'])]
    public function show(User $user, Request $request, EntityManagerInterface $entMngr): JsonResponse
    {
        try{
            if($request -> isMethod('GET')){
                return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
            }
            else if($request -> isMethod('DELETE')){
                $entMngr -> remove($user);
                $entMngr -> flush();

                return $this -> json(['message' => 'Usuario eliminado'], Response::HTTP_OK);
            }

            return $this->json(['error' => 'Something went wrong, if you see this message, contact support.'], Response::HTTP_I_AM_A_TEAPOT);
        }
        catch(\Exception $e){
            return $this -> json(['error' => $e -> getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['PUT'])]
    public function edit(User $user, Request $request, EntityManagerInterface $entMngr, UserPasswordHasherInterface $userPassHash): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);

            if(!$userPassHash -> isPasswordValid($user, trim($data['oldpassword']))){
                return $this -> json(false, Response::HTTP_OK);
            }

            $user -> setName($data['name']);
            $user -> setEmail($data['email']);
            $user -> setPassword($userPassHash -> hashPassword($user, $data['newpassword']));
            $user -> setRoles($data['role']);
            $user -> setBanned($data['banned']);

            $entMngr -> persist($user);
            $entMngr -> flush();

            return $this -> json(true, Response::HTTP_OK, [], ['groups' => 'user:read']);
        }
        catch(\Exception $e){
            return $this -> json(['error' => $e -> getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
