<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationFormType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class ReclamationController extends AbstractController
{
    #[Route('/artiste', name: 'app_artiste')]
    public function index(): Response
    {
        return $this->render('artiste-dash.html.twig', [
            'controller_name' => 'ArtisteController',
        ]);
    }
    #[Route('/listeRec', name: 'app_artiste_listeRec')]
    public function listeRec(ReclamationRepository $repo): Response
    {
        $reclamations = $repo->findAll();
        $rec = new Reclamation();
        $form = $this->createForm(ReclamationFormType::class, $rec);
        return $this->render('artiste/Reclamation.html.twig', ['reclamations' => $reclamations, 'f' => $form->createView()]);
    }
    


   
    



#[Route('/addRec', name: 'app_add_rec')]
public function addRec(Request $request, ManagerRegistry $manager): Response
    {
        $rec = new Reclamation();
        $form = $this->createForm(ReclamationFormType::class, $rec);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $manager->getManager()->persist($rec);
                $manager->getManager()->flush();
                dump($rec);
    
                return $this->redirectToRoute('app_artiste_listeRec');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement.');
            }
        }
    
        // Return a response even when form submission fails
        return $this->render('artiste/addReclamation.html.twig', [
            'f' => $form->createView(),
        ]);
    }





    #[Route('/reclamation/delete/{id}', name: 'app_reclamation_delete')]
    public function deleteRec($id, ManagerRegistry $manager, ReclamationRepository $repo)
    {
        $rec = $repo->find($id);
        $manager->getManager()->remove($rec);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_artiste_listeRec');
    }

    #[Route('/getRec', name: 'app_get_rec')]
    public function getRec(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $rec = $entityManager->getRepository(Reclamation::class)->find($id);

        if (!$rec) {
            return new JsonResponse(['error' => 'Reclamation not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $rec->getId(),
            'type' => $rec->getType(),
            'text' => $rec->getText(),
        ]);
    }

  
    
    
    #[Route('/updateRec/{id}', name: 'app_update_rec')]
    public function updateRec(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);

        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }
        //$reclamation->setType($request->request->get('type')); 
        $reclamation->setText($request->request->get('text')); 
        

        $entityManager->flush();

        return $this->redirectToRoute('app_artiste_listeRec');
    }
    





}
