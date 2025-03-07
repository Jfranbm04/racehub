<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', []);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('main/paneladministrador.html.twig', []);
    }
    #[Route('/login', name: 'app_login_view')]
    public function login(): Response
    {
        return $this->render('main/login.html.twig', []);
    }

    #[Route('/register', name: 'app_register_view')]
    public function register(): Response
    {
        return $this->render('main/register.html.twig', []);
    }
}
