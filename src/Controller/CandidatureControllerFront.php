<?php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use App\Entity\Candidature;
use App\Form\CandidatureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidaturefront')]
class CandidatureControllerFront extends AbstractController
{
    #[Route('/', name: 'app_candidaturefront_index', methods: ['GET'])]
    public function index(CandidatureRepository $candidatureRepository): Response
    {
        return $this->render('candidaturefront/indexfront.html.twig', [
            'candidature' => $candidatureRepository->findAll(),
        ]);
    }
}
