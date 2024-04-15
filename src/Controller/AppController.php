<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Inscription;
use App\Entity\User;
use App\Repository\FormationRepository;
use App\Repository\InscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
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

    #[Route('/formations', name: 'services')]
    public function formations(FormationRepository $repo): Response
    {
        $formations = $repo->findAll();
        return $this->render(
            'app/formations.html.twig',
            ['formations' => $formations]
        );
    }

    #[Route('/details/{id}', name: 'app_formation_details')]
    public function details($id, FormationRepository $repo): Response
    {
        $formation = $repo->find($id);
        return $this->render('formation/index.html.twig', ['formation' => $formation]);
    }

    #[Route('/inscription/{id}', name: 'app_inscription')]
public function inscription($id, ManagerRegistry $manager,InscriptionRepository $repoIns, UserRepository $repoUser, FormationRepository $repo): Response
{
    $userId = 26; //static ba3ed twali b userLoggin

    $entityManager = $manager->getManager();

    $user = $repoUser->find($userId);

    $formation = $repo->find($id);

dump($user);
dump($formation);

    if (!$user || !$formation) {
        return new Response('User or formation not found', Response::HTTP_NOT_FOUND);
    }

    // Check if the user is already registered for this formation
    $check_inscription = $repoIns->findOneBy(['user' => $user, 'formation' => $formation]);

    if ($check_inscription) {
        return new Response('User already registered for this formation', Response::HTTP_BAD_REQUEST);
    }

    
    $inscription = new Inscription();
    $inscription->setUserId($user);
    $inscription->setFormationId($formation);

    $entityManager->persist($inscription);
    $entityManager->flush();

    return $this->redirectToRoute('services');
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
