<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/admin/adminDashboard', name: 'adminDashboard')]
    public function index(Request $request): Response
    {
        
        
        return $this->render('admin/bars.html.twig'
        );
    }
    
/*
    #[Route('/', name: 'UserDashboard2')]
    public function userDashboardd(UserRepository $userRepository): Response
    {
    

        // Render the template, passing the user entity
        return $this->render('user/UserDashboard.html.twig');
    }
*/
    
    #[Route('/user/UserDashboard/{id}', name: 'UserDashboard')]
    public function userDashboard(UserRepository $userRepository, $id): Response
    {
        // Retrieve the user entity based on $id
        $user = $userRepository->find($id);

        // Render the template, passing the user entity
        return $this->render('user/UserDashboard.html.twig', [
            'user' => $user,
        ]);
    }
    


}
