<?php

namespace App\Controller;

use App\Repository\ConcoursRepository;
use App\Entity\Concours;
use App\Form\ConcoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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

    #[Route('/a', name: 'app_concoursfront_participate', methods: ['GET'])]
    public function participate(ConcoursRepository $concoursRepository,MailerInterface $mailer): Response
    {
        $message = (new Email())
        ->from('culturnaskapere@gmail.com')
        ->to('aziz.rihani2002@gmail.com')
        ->subject('Iscription concour Tunart')
        ->html('<p>Vous avez effectuer une inscription dans un concour sur le platform de Tunart</p>')
        ->text('Hello');

    $mailer->send($message);

    return $this->render('concoursfront/indexfront.html.twig', [
        'concours' => $concoursRepository->findAll(),
    ]);

    }

}
