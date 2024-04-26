<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use App\Controller\TwiliosmsController;

class AdminStatsUserStatutController extends AbstractController
{
   

    #[Route('/admin/stats/user/statut', name: 'app_admin_stats_user_statut')]
    public function users( Request $request, UserRepository $userRepository): Response
    {

      // Récupérer le nombre total d'utilisateurs
      $totalUsers = $userRepository->countAllUsers();

      

      // Passer les données au template pour affichage
      return $this->render('user/stat.html.twig', [
          'totalUsers' => $totalUsers,
      ]);
    }

}
