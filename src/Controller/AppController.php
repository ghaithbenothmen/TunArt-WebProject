<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

    #[Route('/', name: 'main')]
    public function main(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route('/app', name: 'index')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('app/about.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route('/services', name: 'services')]
    public function services(): Response
    {
        return $this->render('app/services.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('app/contact.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/features', name: 'features')]
    public function features(): Response
    {
        return $this->render('app/features.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/appointment', name: 'appointment')]
    public function appointment(): Response
    {
        return $this->render('app/appointment.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/team', name: 'team')]
    public function team(): Response
    {
        return $this->render('app/team.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/testimonial', name: 'testimonial')]
    public function testimonial(): Response
    {
        return $this->render('app/testimonial.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/404page', name: '404page')]
    public function page(): Response
    {
        return $this->render('app/404page.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    
}
