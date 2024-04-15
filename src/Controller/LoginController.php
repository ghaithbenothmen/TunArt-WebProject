<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class LoginController extends AbstractController
{


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

















   /*
    #[Route('/loginnn', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, SessionInterface $session)
{
    
       
    // Création du formulaire
    $form = $this->createForm(LoginType::class);
    
    // Gestion de la soumission du formulaire
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Récupération des données du formulaire
        $formData = $form->getData();
        $email = $formData['email'];
        $password = $formData['mdp'];
    
        // Vérification dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy([
            'email' => $email,
            'mdp' => $password,
            'isApproved' => true, // Check if the user is approved
            'isBlocked' => false, // Check if the user is not blocked
        ]);
        
        if ($user !== null) {
            // Récupérer le rôle de l'utilisateur
            $role = $user->getRole(); // Supposons que la méthode getRole existe dans l'entité User
            
            // Debugging: Dump the role to see if it matches
            dump($role);
            
            // Enregistrer les informations de l'utilisateur dans la session
            $session->set('user_id', $user->getId());
            $session->set('role', $role);
            
            // Debugging: Dump the session data
            dump($session->all());
            
            // Rediriger l'utilisateur en fonction de son rôle
            if ($role === 'Admin') {
                // Redirection vers userListAction du UserController si les identifiants sont corrects
                return $this->redirectToRoute('adminDashboard');
            } else {
                // Redirection vers User_Update s'il y a une correspondance dans la base de données
                return $this->redirectToRoute('UserDashboard', ['id' => $user->getId()]);
            }
        }
    }
    
    // Affichage du formulaire de connexion
    return $this->render('user/login.html.twig', [
        'form' => $form->createView(),
    ]);
}

*/

    
    #[Route('/LoginRedirect', name: 'loredirect')]
    public function goLogin(): Response // Correct the return type declaration
    {
        // Replace 'your_route_name' with the actual route name you want to redirect to
        return $this->redirectToRoute('app_login'); // Correct the usage of redirectToRoute
    }








#[Route('/logout', name: 'app_logout')]
public function logout(Request $request, UserRepository $repo): Response
{
    // Remove user session (if stored in session)
    $request->getSession()->remove('user');

    // Create a response with cache control headers to prevent caching
    $response = $this->redirectToRoute('login');
    $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');

    return $response;
}







}




  

 




