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
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use App\Entity\Actualite;
use App\Form\ActualiteType;
use App\Repository\ActualiteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Translation\LocaleSwitcher;


class AppController extends AbstractController
{

    #[Route('/', name: 'main')]
    public function main(FormationRepository $repo): Response
    {
        $formations = $repo->findLimited(6);
    return $this->render('base.html.twig', [
        'controller_name' => 'AppController',
        'formations' => $formations
    ]);
    }

    #[Route('/app', name: 'index')]
    public function index(): Response
    {
        return $this->render('app/indexfront.html.twig', [
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
    #[Route('/login2', name: 'login2')]
    public function login(): Response
    {
        return $this->render('app/login.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/register2', name: 'register2')]
    public function register(): Response
    {
        return $this->render('app/register.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /*
    #[Route('/actualite', name: 'actualite', methods: ['GET'])]
    public function actualite(ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('app/actualite.html.twig', [
            'actualites' => $actualiteRepository->findAll(),
        ]);
    } */

    #[Route('/formations', name: 'services')]
    public function formations(FormationRepository $repo, Request $request): Response
    {
        $sortBy = $request->query->get('sort', 'name_asc');
    
        switch ($sortBy) {
            case 'name_asc':
                $formations = $repo->findBy([], ['nom' => 'ASC']);
                break;
            case 'name_desc':
                $formations = $repo->findBy([], ['nom' => 'DESC']);
                break;
            case 'rate':
                $formations = $repo->findAllSortedByRate();
                break;
            default:
                $formations = $repo->findAll();
        }
    
        return $this->render(
            'app/formations.html.twig',
            ['formations' => $formations, 'sortBy' => $sortBy]
        );
    }
    

    #[Route('/details/{id}', name: 'app_formation_details')]
    public function details($id, RatingsRepository $repoRating, FormationRepository $repo, InscriptionRepository $repoIns, UserRepository $repoUser): Response
    {
        //$userId = 26; // Static user ID, replace with dynamic user ID retrieval logic
        $user = $this->getUser();

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
            'existingRating' => $existingRating
        ]);
    }

   /*  #[Route('/inscription/{id}', name: 'app_inscription')]
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
    } */

    #[Route('/rating/{id}', name: 'app_rating')]
    public function rateFormation(RatingsRepository $repoRating, UserRepository $repoUser, $id, Request $request, ManagerRegistry $manager, FormationRepository $repo): Response
    {
        //$userId = 26; // Static user ID, replace with dynamic user ID retrieval logic

        $user = $this->getUser();

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
   /*   #[Route('/team', name: 'team')]
    public function team(): Response
    {
        return $this->render('app/team.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }  */

    #[Route('/nosArtistes', name: 'team')]
    public function test(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Get all users query
        $usersAll = $userRepository->findAll();

        // Filter artists
        $artists = array_filter($usersAll, function ($user) {
            return in_array('ROLE_ARTISTE', $user->getRoles());
        });
    
        // Paginate the query results
        $users = $paginator->paginate(
            $artists,
            $request->query->getInt('page', 1), // Get the page number from the request, default to 1
            6 // Number of items per page
        );
 //   dump($users);
        return $this->render('app/team.html.twig', ['users' => $users]);
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

    #[Route('/paymentPro', name: 'process_payment')]
    public function processPayment(UserRepository $repoUser,ManagerRegistry $manager,Request $request, FormationRepository $repo): Response
    {
        // Set your Stripe API key
        \Stripe\Stripe::setApiKey('sk_test_51OqD2zFwwP47unkPDwjI0VW2CAMmqra1xmdfGVzzC2SgbMxKc2O36huNoEJiR6qKmlndFVRWRwBqBn03Bsj5PRl500Sy5RjUr8');


        $formationId = $request->request->get('formation_id');
        $formation = $repo->find($formationId);
        dump($formationId);
        // Check if the formation exists
        if (!$formation) {
            return new Response('Formation not found', Response::HTTP_NOT_FOUND);
        }

        // Retrieve the payment details from the form
        $token = $request->request->get('stripeToken');
        $amount = $formation->getPrix() * 100; // Convert to cents
        $currency = 'usd'; // Change to your currency code
        $expMonth = $request->request->get('exp_month');
        $expYear = $request->request->get('exp_year');
        dump($amount);
        try {
            // Charge the customer
            $charge = \Stripe\Charge::create([
                'amount' => $amount,
                'currency' => $currency,
                'description' => 'Example Charge',
                'source' => $token,
                'exp_month' => $expMonth,
                'exp_year' => $expYear
            ]);
            //$userId = 26; // Static user ID, replace with dynamic user ID retrieval logic
            $user = $this->getUser();

            $entityManager = $manager->getManager();
            $inscription = new Inscription();
            $inscription->setUserId($user);
            $inscription->setFormationId($formation);
    
            $entityManager->persist($inscription);
            $entityManager->flush();
    
            // Add a success flash message
            // Payment was successful, process further if needed
           // $this->get('session')->set('formation_id', $formationId); tab3thha b session
           $this->addFlash('success', 'Payment successful! Thank you for your purchase.');//matemchych why
    
           return $this->redirectToRoute('app_formation_details', [
            'id' => $formationId,
            'payment_success' => true,
        ]);

        } catch (\Exception $e) {
            // Payment failed, handle the error
            dump($e);
            return $this->redirectToRoute('app_formation_details', [
                'id' => $formationId,
                'payment_failure' => true,
            ]);
        }
    }

    #[Route('/payment/{id}', name: 'app_payment')]
    public function showPaymentPage($id, FormationRepository $repo): Response
    {
        $user=$this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $formation = $repo->find($id);
        if (!$formation) {
            return new Response('Formation not found', Response::HTTP_NOT_FOUND);
        }

        return $this->render('formation/payment.html.twig', [
            'formation' => $formation
        ]);
    }
    /* #[Route('/payment-success', name: 'payment_success')]
    public function paymentSuccess(FormationRepository $repo): Response
    {
        $formationId = $this->get('session')->get('formation_id'); //recupere b session 

        // Retrieve the formation entity
        $formation = $repo->find($formationId);
    
        if (!$formation) {
            // Handle the case where the formation is not found
            $this->addFlash('error', 'Formation not found');
            return $this->redirectToRoute('payment_failure');
        }
    
        // Add a success flash message
        $this->addFlash('success', 'Payment successful! Thank you for your purchase.');
    
        // Redirect to the details page of the formation
        return $this->redirectToRoute('app_formation_details', ['id' => $formationId]);
    } */
    #[Route('/actualite', name: 'actualite')]
    public function translate(TranslatorInterface $translator, Request $request, ActualiteRepository $actualiteRepository): Response
    {
    $lang = $request->query->get('lang', 'fr');

    $actualite = $actualiteRepository->findAll();

    $translated = [];

    foreach ($actualite as $unActualite) {
        $translated[] = [
            'titre' => $translator->trans($unActualite->getTitre(), [], null, $lang),
            'text' => $unActualite->getText(),
            'image' => $unActualite->getImage(),
            'date' => $unActualite->getDate(),
            'id' => $unActualite->getId(),
        ];
    }

    return $this->render('app/actualite.html.twig', [
        'translated' => $translated,
    ]);
}
}
