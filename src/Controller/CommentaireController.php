<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Actualite;
use App\Form\ActualiteType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ForbiddenWordChecker;
use Symfony\Component\Security\Core\Security;



class CommentaireController extends AbstractController
{
    private $forbiddenWordChecker;

    public function __construct(ForbiddenWordChecker $forbiddenWordChecker)
    {
        $this->forbiddenWordChecker = $forbiddenWordChecker;
    }
    #[Route('/admin/adminDashboard', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin-dash.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

    #[Route('/admin/adminDashboard/commentaire', name: 'app_commentaire_index', methods: ['GET'])]
    public function commentaire(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/admin/adminDashboard/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/admin/adminDashboard/{idC}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/admin/adminDashboard/{idC}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{idC}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdC(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/user/{id}/addCommentaire', name: 'addCommentaire', methods: ['GET', 'POST'])]
    public function addcommentaire(Actualite $actualite,UserRepository $repoUser, Request $request ,EntityManagerInterface $entityManager,Security $security)
    {

        $commentText  = $request->request->get('commentaire');
        if ($this->forbiddenWordChecker->containsForbiddenWord($commentText)) {
            return new Response('Inappropriate content', 403);

        }
        else {

        $user = $security->getUser();

         if(!$user){
                return $this->redirectToRoute('app_login');
         }
        $commentaire = new Commentaire(); // Créez une nouvelle instance de votre entité Commentaire
        $commentaire->setActualite($actualite);
        //$user_id = 26; //static ba3ed twali b userLoggin
        //$user = $repoUser->find($user_id);
        //$user= $this->getUser(); //static ba3ed twali b userLoggin
        //$user = $repoUser->find($user);
        $commentaire->setUser($user);
        $commentaire->setContenuC($request->request->get('commentaire')); // Assurez-vous que 'contenuC' est le nom du champ dans votre formulaire
       
        $em = $this->getDoctrine()->getManager();
        $em->persist($commentaire);
        $em->flush();
    
        return $this->redirectToRoute('app_actualite_commentaire', ['id' => $actualite->getId()]);
    }
}
}
