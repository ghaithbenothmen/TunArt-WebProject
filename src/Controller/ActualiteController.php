<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use DateTime;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Entity\Actualite;
use App\Form\ActualiteType;
use App\Repository\ActualiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\SmsGeneratorAct;


#[Route('/admin')]
class ActualiteController extends AbstractController
{
    #[Route('/actualite/search', name: 'app_actualite_search', methods: ['GET'])]
    public function search(Request $request, ActualiteRepository $actualiteRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
        if ($query) {
            $actualites = $actualiteRepository->createQueryBuilder('a')
                ->where('a.titre LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery();
    
            
            $pagination = $paginator->paginate(
                $actualites, 
                $request->query->getInt('page', 1), 
                10 
            );
        } else {
            
            $pagination = null; 
        }
    
        return $this->render('actualite/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    #[Route('/actualite/tri', name: 'app_actualitesback_tri', methods: ['GET'])]
    public function tri(Request $request, ActualiteRepository $actualiteRepository, PaginatorInterface $paginator): Response
    {
        $order = $request->query->get('order', 'asc'); 
        $field = $request->query->get('field', 'titre'); 
    
        if (!in_array(strtolower($order), ['asc', 'desc'])) {
            $order = 'asc'; 
        }
    
        if (!in_array($field, ['titre', 'date'])) {
            $field = 'titre'; 
        }

        $queryBuilder = $actualiteRepository->createQueryBuilder('a')
            ->orderBy('a.' . $field, $order);
    
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10 
        );
        return $this->render('actualite/index.html.twig', [
            'pagination' => $pagination,
            
        ]);
    }
    
   
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin-dash.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }

    /*#[Route('/actualite', name: 'app_actualite_index', methods: ['GET'])]
    public function actualite(ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('actualite/index.html.twig', [
            'actualites' => $actualiteRepository->findAll(),
        ]);
    }*/
    #[Route('/actualite', name: 'app_actualite_index', methods: ['GET'])]
public function actualite (Request $request, ActualiteRepository $actualiteRepository, PaginatorInterface $paginator): Response
{
    $pagination = $paginator->paginate(
        $actualiteRepository->findAll(), 
        $request->query->getInt('page', 1), 
        2
    );

    return $this->render('actualite/index.html.twig', [
        'pagination' => $pagination,
    ]);
}

    #[Route('/actualite/new', name: 'app_actualite_new', methods: ['GET', 'POST'])]
    public function new(SmsGeneratorAct $smsGeneratorAct,Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentDate = new DateTime();
        $actualite = new Actualite($currentDate);
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // This ensures that the filename is unique
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
    
                // Update the image path in the actualite entity
                $actualite->setImage($newFilename);
            }

            $entityManager->persist($actualite);
            $entityManager->flush();
            $name = 'TunArt';
            $text = 'Un nouveau actualité a été ajouté : ' . $actualite->getTitre();
            $smsGeneratorAct->SendSms('+21699975050',$name, $text);

            return $this->redirectToRoute('app_actualite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('actualite/new.html.twig', [
            'actualite' => $actualite,
            'form' => $form,
        ]);
    }

    #[Route('/actualite/{id}', name: 'app_actualite_show', methods: ['GET'])]
    public function show(Actualite $actualite): Response
    {
        return $this->render('actualite/show.html.twig', [
            'actualite' => $actualite,
        ]);
    }

    #[Route('/actualite/{id}/edit', name: 'app_actualite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Actualite $actualite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // This ensures that the filename is unique
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
    
                // Update the image path in the actualite entity
                $actualite->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_actualite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('actualite/edit.html.twig', [
            'actualite' => $actualite,
            'form' => $form,
        ]);
    }

    #[Route('/actualite/{id}', name: 'app_actualite_delete', methods: ['POST'])]
    public function delete(Request $request, Actualite $actualite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actualite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($actualite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_actualite_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/actualite/{id}/commentaire', name: 'app_actualite_commentaire', methods: ['GET', 'POST'])]
    public function commentaire(Request $request, Actualite $actualite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // This ensures that the filename is unique
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
    
                // Update the image path in the actualite entity
                $actualite->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_actualite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('app/commentaire.html.twig', [
            'actualite' => $actualite,
            'form' => $form,
        ]);
    }


    #[Route('/actualite/{id}/like', name: 'app_actualite_like_actualite', methods: ['GET'])]
    public function likeActualite(Request $request, ActualiteRepository $actualiteRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $actualite = $actualiteRepository->find($id);
        $actualite->setLiked(1);
        $entityManager->persist($actualite);
        $entityManager->flush();
        // Après avoir liké, restez sur la même page
        return $this->redirectToRoute('app_actualite_index');
    }
    
    //dislike actualite
    #[Route('/actualite/{id}/dislike', name: 'app_actualite_dislike_actualite', methods: ['GET'])]
    public function dislikeActualite(Request $request, ActualiteRepository $actualiteRepository,EntityManagerInterface $entityManager, $id): Response
    {
        $actualite = $actualiteRepository->find($id);
        $actualite->setLiked(-1);
        $entityManager->persist($actualite);
        $entityManager->flush();
        // After dislike, stay on the same page
        return $this->redirectToRoute('app_actualite_index');
    }
/*

    #[Route('/actualite/{id}', name: 'app_actualite_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Actualite $actualite,Security $security, CommentaireRepository $commentaireRepository, HttpClientInterface $httpClient): Response
    {
       
    

        $commentaire= new commentaire();
        $commentaireform = $this->createForm(CommentaireType::class, $commentaire);
        
        $commentaireform->handleRequest($request);
        if ($commentaireform->isSubmitted() && $commentaireform->isValid()) {
            $commentaireform->setId($actualite);
            $commentaireform->setUpvotes(0);
            $commentaireform->setCreatedatcomment(new DateTime());
            $commentaireform->setUsername($security->getUser());
            //filter for bad words:
                $contenuc = $commentaire->getContenuc();
                $response = $httpClient->request('GET', 'https://neutrinoapi.net/bad-word-filter', [
                    'query' => [
                        'contenuc' => $contenuc
                    ],
                    'headers' => [
                        'User-ID' => 'helmi',
                        'API-Key' => 'BTc6k6CBMZi276icxBccQbSu9lQSu2wVT4euqZsRRvHPdY9u',//nSNxcobfbZCjJAon06ibWRgy3C6BR5HX90a27YYi0tW17WWW
                    ]
                ]);
        
                if ($response->getStatusCode() === 200) {
                    $result = $response->toArray();
                    if ($result['is-bad']) {
                        // Handle bad word found
                        $this->addFlash('danger', '</i>Your comment contains inappropriate language and cannot be posted.');
                        return $this->redirectToRoute('app_actualite_show', ['id' => $actualite->getId()], Response::HTTP_SEE_OTHER);
                    } else {
                        // Save comment
                        $this->addFlash('success', 'Your comment has been posted.');

                        $commentaireRepository->save($commentaire, true);
                        return $this->redirectToRoute('app_actualite_show', ['id' => $actualite->getId()], Response::HTTP_SEE_OTHER);
                    }
                } else {
                    // Handle API error
                    
                    return new Response("Error processing request", Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        if($actualite->isVisible() == false){
            return $this->redirectToRoute('app_show_all', [], Response::HTTP_SEE_OTHER);
        }
         if (!$actualite) {
            // Invalid post ID, redirect to app_show_all
            return $this->redirectToRoute('app_show_all', [], Response::HTTP_SEE_OTHER);
        } 
        return $this->render('actualite/show.html.twig', [
            'actualite' => $actualite,
            'commentaires' => $commentaireRepository->findBy(['Id' => $actualite->getId()]),
            'commentaire_form' => $commentaireform->createView(),
            'user' => $security->getUser() 
        ]);

    }
    */


}
