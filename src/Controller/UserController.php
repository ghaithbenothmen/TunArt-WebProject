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
        $this->twilioClient = $twilioClient;
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
           
         


           // Get telephone number from the form
          $tel = '+216' . $form->get('tel')->getData();
           $user->setTel($tel);

            try {
                // Send SMS to the registered user
                $toNumber = $user->getTel();
                $fromNumber = '+18588793064';
        
                $message = $twilioClient->messages->create(
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









    #[Route('/users', name: 'user_list')]
    public function userList(Request $request, UserRepository $userRepository): Response
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
        
        // Render the template and pass the users to it
        return $this->render('user/user_list.html.twig', [
            'users' => $users,
        ]);
    }
    




/*
    #[Route('/users', name: 'user_list')]
    public function userList(): Response
    {
        // Fetch all users from the database
        $userRepository = $this->managerRegistry->getRepository(User::class);
        $users = $userRepository->findAll();

        // Render the template and pass the users to it
        return $this->render('user/user_list.html.twig', [
            'users' => $users,
        ]);
    }
*/





    #[Route('/user/{id}/update', name: 'update_user')]
    public function update(Request $request, int $id): Response
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

            return $this->redirectToRoute('UserDashboard');
        }

        return $this->render('user/update.html.twig', [
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

  

    #[Route('/user/{id}/delete', name: 'delete_user', methods: ['POST'])]
    public function delete(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);
    
        $user = $userRepository->find($id);
       
                $entityManager->remove($user);
                $entityManager->flush();
    
                $this->addFlash('success', 'User deleted successfully.');
           
    
        return $this->redirectToRoute('userss');
    }




    #[Route('/user/{id}/reactivate', name: 'reactivate_user', methods: ['POST'])]
    public function reactivateUser(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        
        $entityManager->flush();

        // Redirect back to the user list
        return $this->redirectToRoute('userss');
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
#[Route('/user/{id}/updateUser', name: 'User_Upda')]
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

    return $this->render('user/user_Card.html.twig', [
        'user' => $user,
        'registration_form' => $form->createView(),
    ]);
}





// Ban User
    #[Route('/user/{id}/Ban', name: 'ban_user', methods: ['POST'])]
    public function banUser(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

    
        $entityManager->flush();

        // Redirect back to the user list
        return $this->redirectToRoute('login');
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




