<?php

namespace App\Controller;

use App\Repository\ConcoursRepository;
use App\Repository\UserRepository;
use App\Entity\Concours;
use App\Entity\User;
use App\Entity\Candidature;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ConcoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
#[Route('/concoursfront')]
class ConcoursControllerFront extends AbstractController
{
    #[Route('/', name: 'app_concoursfront_index', methods: ['GET'])]
    public function index(Request $request,ConcoursRepository $concoursRepository, PaginatorInterface $paginator): Response
    {
        //all concours

        $pagination = $paginator->paginate(
            $concoursRepository->findNonOutdated(), 
            $request->query->getInt('page', 1), 
            5
        );

        return $this->render('concoursfront/indexfront.html.twig', [
            'pagination' => $pagination,
        ]);

        //Concours not outdated

        $query = $request->query->get('query');
        
        $queryBuilder = $concoursRepository->createQueryBuilder('a');
        if ($query) {
            $queryBuilder->andWhere('a.nom LIKE :query')
                ->setParameter('query', '%'.$query.'%');
               
        }
        $concours = $queryBuilder->getQuery()->getResult();
        return $this->render('concoursfront/indexfront.html.twig', [
            'concours' => $concours,
        ]);

    }



    #[Route('/{refrence}', name: 'app_concoursfront_participate', methods: ['GET'])]
    public function participate(Request $request,Concours $concour,ConcoursRepository $concoursRepository,MailerInterface $mailer, EntityManagerInterface $entityManager,UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->render('concoursfront/popup.html.twig');
        }
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
            $candidature->getIdUser()->getId()))
            {
                echo "<script>alert('Vous etes deja inscrit!');</script>";
            }
            else
        {
            $concour->setNparticipant($concour->getNparticipant()+1);
            $entityManager->persist($candidature);
            $entityManager->flush();
            $message = (new Email())
            ->from('culturnaskapere@gmail.com')
            ->to('aziz.rihani2002@gmail.com')
            ->subject('Iscription concour Tunart')
            ->html($this->renderView('concoursfront/email.html.twig', [
                'concour' => $concour,
            ]));
            $mailer->send($message);
        }

        $pagination = $paginator->paginate(
            $concoursRepository->findNonOutdated(), 
            $request->query->getInt('page', 1), 
            5
        );

        return $this->render('concoursfront/indexfront.html.twig', [
            'pagination' => $pagination,
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

    #[Route('/search', name: 'app_concoursfront_search', methods: ['GET'])]
    public function search(Request $request, ConcoursRepository $concoursRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
        if ($query) {
            $concours = $concoursRepository->createQueryBuilder('a')
                ->where('a.nom LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery();
        }

        return $this->render('concoursfront/indexfront.html.twig', [
            'concours' => $concours,
        ]);
    }
}
