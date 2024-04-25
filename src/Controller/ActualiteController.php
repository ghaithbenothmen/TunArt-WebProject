<?php

namespace App\Controller;

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

#[Route('/admin')]
class ActualiteController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin-dash.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }

    #[Route('/actualite', name: 'app_actualite_index', methods: ['GET'])]
    public function actualite(ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('actualite/index.html.twig', [
            'actualites' => $actualiteRepository->findAll(),
        ]);
    }

    #[Route('/actualite/new', name: 'app_actualite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
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

}
