<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Inscription;
use App\Entity\Ratings;
use App\Repository\RatingsRepository;
use App\Entity\User;
use App\Repository\FormationRepository;
use App\Repository\InscriptionRepository;
use App\Repository\RatingsRepository as RepositoryRatingsRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function details($id,RatingsRepository $repoRating, FormationRepository $repo, InscriptionRepository $repoIns, UserRepository $repoUser): Response
    {
        $userId = 26; // Static user ID, replace with dynamic user ID retrieval logic
        $user = $repoUser->find($userId);
    
        $formation = $repo->find($id);
        if (!$formation) {
            return new Response('Formation not found', Response::HTTP_NOT_FOUND);
        }
    
        $check_inscription = $repoIns->findOneBy(['user' => $user, 'formation' => $formation]);
        $existingRating = $repoRating->findOneBy(['user' => $user, 'formation' => $formation]);
        $ratingDisabled = $existingRating !== null;
        
        return $this->render('formation/index.html.twig', [
            'formation' => $formation,
            'ratingDisabled' => $ratingDisabled,
            'user_already_registered' => $check_inscription,
            'existingRating'=>$existingRating
        ]);
    }
    
    #[Route('/inscription/{id}', name: 'app_inscription')]
    public function inscription($id, ManagerRegistry $manager, InscriptionRepository $repoIns, UserRepository $repoUser, FormationRepository $repo): Response
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

    #[Route('/rating/{id}', name: 'app_rating')]
    public function rateFormation(RatingsRepository $repoRating,UserRepository $repoUser,$id, Request $request, ManagerRegistry $manager, FormationRepository $repo): Response
    {
        $userId = 26; // Static user ID, replace with dynamic user ID retrieval logic
    
        $user = $repoUser->find($userId);

        $entityManager = $manager->getManager();
    
        $formation = $repo->find($id);
    
        if (!$formation) {
            return new Response('Formation not found', Response::HTTP_NOT_FOUND);
        }
    
        // Check if the user has already rated this formation
        $existingRating = $repoRating->findOneBy(['user' => $user, 'formation' => $formation]);
        $ratingDisabled = $existingRating !== null;
        if ($existingRating) {
            return new Response('User has already rated this formation', Response::HTTP_BAD_REQUEST);
        }
    
        // Process the rating value from the request
        $ratingValue = $request->query->get('ratingValue');
        $ratingValue2 = (float) $ratingValue;
        dump($ratingValue);
        // Validate the rating value (e.g., ensure it's between 1 and 5)
    
        // Create a new Rating entity and set its properties
        $rating = new Ratings();
        $rating->setUser($user);
        $rating->setFormation($formation);
        $rating->setRatingValue($ratingValue2);
    
        // Persist the new rating
        $entityManager->persist($rating);
        $entityManager->flush();
    
        return new Response('Rating saved successfully', Response::HTTP_OK);
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
