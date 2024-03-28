<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtisteController extends AbstractController
{
    #[Route('/artiste', name: 'app_artiste')]
    public function index(): Response
    {
        return $this->render('artiste-dash.html.twig', [
            'controller_name' => 'ArtisteController',
        ]);
    }
    #[Route('/liste', name: 'app_artiste_liste')]
    public function liste(): Response
    {
        return $this->render('artiste/categorie.html.twig', [
            'controller_name' => 'ArtisteController',
        ]);
    }
}
