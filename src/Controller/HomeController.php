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
        

        return $this->render('admin-dash.html.twig'
        );
    }
    

    #[Route('/artiste/artisteDashboard/{id}', name: 'artisteDashboard')]
    public function artisteDashboardd(UserRepository $userRepository): Response
    {
    

        // Retrieve the user entity based on $id
        $user = $userRepository->find($id);

        // Render the template, passing the user entity
        return $this->render('artiste-dash.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/', name: 'dash')]
    public function dash(): Response
    {
        // Simply render the template without passing the user entity
        return $this->render('base.html.twig');
    }


    #[Route('/user/UserDashboard/{id}', name: 'UserDashboard')]
    public function userDashboard(UserRepository $userRepository, int $id): Response
    {
        // Retrieve the user entity based on $id
        $user = $userRepository->find($id);

        if (!$user) {
            // If no user is found, throw a 404 Not Found exception
            throw new NotFoundHttpException('No user found for id ' . $id);
        }

        // Render the template, passing the user entity
        return $this->render('baseClient.html.twig', [
            'user' => $user,
        ]);
    }


}
