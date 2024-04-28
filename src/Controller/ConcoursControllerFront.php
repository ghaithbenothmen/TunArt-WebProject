<?php

namespace App\Controller;

use App\Repository\ConcoursRepository;
use App\Entity\Concours;
use App\Entity\User;
use App\Entity\Candidature;
use DateTime;
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

    
    #[Route('/{refrence}', name: 'app_concoursfront_participate', methods: ['GET'])]
    public function participate(Concours $concour,ConcoursRepository $concoursRepository,MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $entityManager->persist($user);
        $user->setIIdUser(25);
        echo $user->getIdUser();
        $currentDate = new DateTime();
        $candidature = new Candidature($currentDate,$user,$concour);
        $candidature->setIdConcours($concour);
        $candidature->setIdUser($user);
        if($concour->getNparticipant()==$concour->getMaxparticipant())
        {
            echo "<script>alert('Maximum participants reached!');</script>";
        }
        else if($concour->getDate()<$candidature->getDate())
            {
                echo "<script>alert('Date depasse!');</script>";
            }
            else if($this->checkCandidature($candidature->getIdConcours()->getRefrence(),
            $candidature->getIdUser()->getIdUser()))
            {
                echo "<script>alert('Vous etes deja inscrit!');</script>";
            }
            else
        {
            $concour->setNparticipant($concour->getNparticipant()+1);
            echo $concour->getRefrence();
            $entityManager->persist($candidature);
            $entityManager->flush();
            $message = (new Email())
            ->from('culturnaskapere@gmail.com')
            ->to('aziz.rihani2002@gmail.com')
            ->subject('Iscription concour Tunart')
            ->html('<p>Vous avez effectuer une inscription dans un concour sur le platform de Tunart</p>')
            ->text('Hello');
    
        //$mailer->send($message);
        }

    return $this->render('concoursfront/indexfront.html.twig', [
        'concours' => $concoursRepository->findAll(),
    ]);

    }

    private function checkCandidature(int $idConcours, int $idUser): bool
    {
        // Get the Doctrine EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Get the Candidature repository
        $candidatureRepository = $entityManager->getRepository(Candidature::class);

        // Find the candidature by concours and user IDs
        $candidature = $candidatureRepository->findOneBy(['Concours' => $idConcours, 'user' => $idUser]);

        // Check if candidature exists
        return ($candidature !== null);
    }

}
