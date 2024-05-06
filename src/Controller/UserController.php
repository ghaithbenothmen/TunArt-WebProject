<?php

namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface; 
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use App\Controller\TwiliosmsController;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;






class UserController extends AbstractController
{
    private $managerRegistry;
    private $entityManager;
    private $slugger;
    private $twilioClient;

    public function __construct(ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager, SluggerInterface $slugger , LoggerInterface $logger, Client $twilioClient)
{
    $this->managerRegistry = $managerRegistry;
    $this->entityManager = $entityManager;
    $this->slugger = $slugger;
    $this->logger = $logger;
    $this->twilioClient = $twilioClient; // Injected Client instance
}





    #[Route('/register', name: 'app_registerr')]
public function register(
    Request $request,
    UserRepository $userRepository,
    LoggerInterface $logger,
    FlashBagInterface $flashBag
): Response {
    $user = new User();
    $form = $this->createForm(RegisterUserType::class, $user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        
        // Check if a user with the submitted email already exists
        $existingUser = $userRepository->searchByEmail($user->getEmail());
        if ($existingUser) {
            // Handle case where user with the same email already exists
            $flashBag->add('error', 'An account with this email already exists.');
            return $this->redirectToRoute('app_registerr');
        }

        // Handle file upload
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            // Generate a unique name for the file
            $imageName = md5(uniqid()).'.'.$imageFile->guessExtension();

            // Move the file to the directory where images are stored
            $imageFile->move(
                $this->getParameter('user_directory'),
                $imageName
            );

            // Set the image name in the user entity
            $user->setImage($imageName);
        }

        

        
      
        // Persist the user to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();


            $nom =  $form->get('nom')->getData();
            $prenom =  $form->get('prenom')->getData();
            $email = $form->get('email')->getData();
            $mdp =  $form->get('mdp')->getData();
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            

            

          


           // Get telephone number from the form
          $tel = '+216' . $form->get('tel')->getData();
           $user->setTel($tel);

            try {
                // Send SMS to the registered user
                $toNumber = $user->getTel();
                $fromNumber = '+18588793064';
        
                $message = $this->twilioClient->messages->create(
                    $toNumber,
                    [
                        'from' => $fromNumber,
                        'body' => 'Hello ' . $nom , $prenom. ' ! You have been successfully registered. Your email is: ' . $email . ' and your password is: ' . $mdp,
        ]
                );
                
                $logger->info('SMS sent with ID: ' . $message->sid);
            } catch (\Exception $e) {
                $logger->error('Failed to send SMS: ' . $e->getMessage());
            }

            // Redirect to another page after successful registration
            return $this->redirectToRoute('login');
        }

        return $this->render('user/register.html.twig', [
            'registration_form' => $form->createView(),
        ]);
    }







    #[Route('/admin/users', name: 'user_list')]
public function userList(Request $request, PaginatorInterface $paginator, UserRepository $userRepository): Response
{
    // Get the sort option from the request query parameters
    $sortBy = $request->query->get('sort');

    // Get the search term from the request query parameters
    $searchTerm = $request->query->get('search');
    
    // Initialize $users variable
    $users = [];

    // If sorting by email is requested, fetch users sorted by email
    if ($sortBy === 'email') {
        $users = $userRepository->findAllSortedByEmail();
    } elseif ($searchTerm) {
        // If search term is provided, fetch users by email search
        $users = $userRepository->searchByEmail($searchTerm);
    } else {
        // Otherwise, fetch all users
        $users = $userRepository->findAll();
    }

    // Paginate the results
    $users = $paginator->paginate(
        $users,
        $request->query->getInt('page', 1), // Get the page number from the request, default to 1
        6 // Number of items per page
    );

    // Render the template and pass the users to it
    return $this->render('user/user_list.html.twig', [
        'users' => $users,
    ]);
}


    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin-dash.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    







    public function update(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $userRepository->find($id);
        $this->logger->info('User found: ' . $user->getId());

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->logger->info('Form submitted and valid');
            
            // Mettre à jour l'entité User
            $entityManager->flush();
            $this->logger->info('User updated successfully.');

            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('UserDashboard', ['id' => $user->getId()]);
        }

        $this->logger->info('Form not submitted or invalid');

        return $this->render('user/user_Card.html.twig', [
            'user' => $user,
            'registration_form' => $form->createView(),
        ]);
    }


    public function updateArtiste(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $userRepository->find($id);
        $this->logger->info('Artiste found: ' . $user->getId());

        if (!$user) {
            throw $this->createNotFoundException('Artiste not found');
        }

        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->logger->info('Form submitted and valid');
            
            // Mettre à jour l'entité User
            $entityManager->flush();
            $this->logger->info('Artiste updated successfully.');

            $this->addFlash('success', 'Artiste updated successfully.');

            return $this->redirectToRoute('artisteDashboard', ['id' => $user->getId()]);
        }

        $this->logger->info('Form not submitted or invalid');

        return $this->render('user/user_Card.html.twig', [
            'user' => $user,
            'registration_form' => $form->createView(),
        ]);
    }






    #[Route('/user/{id}/updateAdmin', name: 'update_userAdmin')]
    public function updateUserAdmin(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('adminDashboard');
        }

        return $this->render('user/updateAdmin.html.twig', [
            'user' => $user,
            'registration_form' => $form->createView(),
        ]);
    }






    private $logger;

  
    public function delete(Request $request, int $id, TokenStorageInterface $tokenStorage): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);
    
        $user = $userRepository->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $entityManager->remove($user);
        $entityManager->flush();
    
        // Supprime l'utilisateur de la session s'il est actuellement authentifié
        $token = $tokenStorage->getToken();
        if ($token && $token->getUser() === $user) {
            $tokenStorage->setToken(null);
            $request->getSession()->invalidate();
        }
    
        $this->addFlash('success', 'User deleted successfully.');
    
        return $this->redirectToRoute('app_login');
    }

    

    #[Route('/admin/user/{id}/delete', name: 'delete_user', methods: ['POST'])]
    public function deleteUser(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);
    
        $user = $userRepository->find($id);
       
                $entityManager->remove($user);
                $entityManager->flush();
    
                $this->addFlash('success', 'User deleted successfully.');
           
    
        return $this->redirectToRoute('user_list');
    }



    







// Tri recherche ........
public function sortByEmail(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllSortedByEmail();

        return $this->render('user/search.html.twig', [
            'users' => $users,
        ]);
    }






// User Interface Update have ban button
#[Route('/admin/user/{id}/updateUser', name: 'User_Upda')]
public function updateUser(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $userRepository = $this->getDoctrine()->getRepository(User::class);

    $user = $userRepository->find($id);
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Create form for updating user fields
    $form = $this->createForm(RegisterUserType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            // Generate a unique name for the file
            $imageName = md5(uniqid()).'.'.$imageFile->guessExtension();

            // Move the file to the directory where images are stored
            $imageFile->move(
                $this->getParameter('user_directory'),
                $imageName
            );

            // Set the image name in the user entity
            $user->setImage($imageName);
        }

        // Update other fields
        $entityManager->flush();

        $this->addFlash('success', 'User updated successfully.');

        return $this->redirectToRoute('UserDashboard', ['id' => $user->getId()]);
    }

    return $this->render('user/profil.html.twig', [
        'user' => $user,
        'registration_form' => $form->createView(),
    ]);
}






    









    #[Route(path: '/userss', name: 'userss')]
    public function users( Request $request,UserRepository $userRepository): Response
    {
          // Get the sort option from the request query parameters
          $sortBy = $request->query->get('sort');
        
          // If sorting by email is requested, fetch users sorted by email
          if ($sortBy === 'email') {
              $users = $userRepository->findAllSortedByEmail();
          } else {
              // Otherwise, fetch all users
              $users = $userRepository->findAll();
          }

        return $this->render('/user/usertest.html.twig', ['users' => $users]);
    }

   






    #[Route(path:'/downloadUserListPdf', name: 'downloadUserListPdf')]
    public function downloadUserListPdf(): Response
    {
        // Get the list of users from the database
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        // Render the PDF template with user data
        $html = $this->renderView('user/user_list_pdf.html.twig', [
            'users' => $users,
        ]);

        // Instantiate Dompdf
        $dompdf = new Dompdf();

        // Load HTML content
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Generate filename
        $filename = 'user_list_' . date('Ymd_His') . '.pdf';

        // Output PDF to browser for download
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]
        );
    }










}




