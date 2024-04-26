<?php

namespace App\Controller;

use App\Repository\ConcoursRepository;
use App\Entity\Concours;
use App\Form\ConcoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/concoursfront')]
class ConcoursControllerFront extends AbstractController
{
    #[Route('/', name: 'app_concoursfront_index', methods: ['GET'])]
    public function index(ConcoursRepository $concoursRepository): Response
    {
        return $this->render('concoursfront/indexfront.html.twig', [
            'concours' => $concoursRepository->findAll(),
        ]);
    }

    
}
