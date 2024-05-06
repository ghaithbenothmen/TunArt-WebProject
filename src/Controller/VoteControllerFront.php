<?php

namespace App\Controller;

use App\Repository\ConcoursRepository;
use App\Repository\UserRepository;
use App\Entity\Concours;
use App\Entity\User;
use App\Entity\Vote;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ConcoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/concoursfrontvote')]
class ConcoursControllerFrontVote extends AbstractController
{
    #[Route('/', name: 'app_concoursfront_vote_index', methods: ['GET'])]
    public function index(Request $request,ConcoursRepository $concoursRepository, PaginatorInterface $paginator): Response
    {
        //all concours

        $pagination = $paginator->paginate(
            $concoursRepository->findNonOutdated(), 
            $request->query->getInt('page', 1), 
            5
        );
        return $this->render('concoursfront/indexfrontvote.html.twig', [
            'pagination' => $pagination,
        ]);

        //Concours not outdateda

        $query = $request->query->get('query');
        
        $queryBuilder = $concoursRepository->createQueryBuilder('a');
        if ($query) {
            $queryBuilder->andWhere('a.nom LIKE :query')
                ->setParameter('query', '%'.$query.'%');
               echo 11;
        }
        $concours = $queryBuilder->getQuery()->getResult();
        return $this->render('concoursfront/indexfrontvote.html.twig', [
            'concours' => $concours,
        ]);

    }



    #[Route('/{refrence}', name: 'app_concoursfront_vote', methods: ['GET'])]
    public function participate(Request $request,Concours $concour,ConcoursRepository $concoursRepository,MailerInterface $mailer, EntityManagerInterface $entityManager,UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $user = new User();
        $entityManager->persist($user);
        $user = $userRepository->findOneBySomeField(20);
        echo $user->getIdUser();
        $currentDate = new DateTime();
        $vote = new Vote($currentDate,$user,$concour);
        $vote->setIdConcours($concour);
        $vote->setIdUser($user);
        if($this->checkVote($vote->getIdConcours()->getRefrence(),
            $vote->getIdUser()->getIdUser()))
            {
                echo "<script>alert('Vous étes deja inscrit!');</script>";
            }
            else
        {
            $concour->setNparticipant($concour->getNparticipant()+1);
            echo $concour->getRefrence();
            $entityManager->persist($vote);
            $entityManager->flush();
        }
        $pagination = $paginator->paginate(
            $concoursRepository->findNonOutdated(), 
            $request->query->getInt('page', 1), 
            5
        );

        return $this->render('concoursfront/indexfrontvote.html.twig', [
            'pagination' => $pagination,
        ]);

    }

    private function checkVote(int $idConcours, int $idUser): bool
    {
        // Get the Doctrine EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Get the vote repository
        $VoteRepository = $entityManager->getRepository(Vote::class);

        // Find the vote by concours and user IDs
        $vote = $VoteRepository->findOneBy(['Concours' => $idConcours, 'user' => $idUser]);

        // Check if vote exists
        return ($vote !== null);
    }

    #[Route('/search', name: 'app_concoursfront_vote_search', methods: ['GET'])]
    public function search(Request $request, ConcoursRepository $concoursRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
        if ($query) {
            $concours = $concoursRepository->createQueryBuilder('a')
                ->where('a.nom LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery();
        }

        return $this->render('concoursfront/indexfrontvote.html.twig', [
            'concours' => $concours,
        ]);
    }
}
