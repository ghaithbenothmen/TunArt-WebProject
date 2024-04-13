<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieFormType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArtisteController extends AbstractController
{
    #[Route('/artiste', name: 'app_artiste')]
    public function index(): Response
    {
        return $this->render('artiste-dash.html.twig', [
            'controller_name' => 'ArtisteController',
        ]);
    }
    #[Route('/listeCat', name: 'app_artiste_listeCat')]
    public function listeCat(CategorieRepository $repo): Response
    {
        $categories = $repo->findAll();
        $cat = new Categorie();
        $form = $this->createForm(CategorieFormType::class, $cat);
        return $this->render('artiste/categorie.html.twig', ['categories' => $categories, 'f' => $form->createView()]);
    }


    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childErrors = $this->getErrorsFromForm($childForm)) {
                $errors[$childForm->getName()] = $childErrors;
            }
        }

        return $errors;
    }

    #[Route('/addCat', name: 'app_add_cat')]
    public function addCat(Request $request, ManagerRegistry $manager): Response
    {
        $cat = new Categorie();
        $form = $this->createForm(CategorieFormType::class, $cat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $manager->getManager()->persist($cat);
                $manager->getManager()->flush();
    
                return new JsonResponse(['success' => true]);
            } catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    
        // Get errors from the form
        $errors = $this->getFormErrors($form);
    
        return new JsonResponse(['success' => false, 'errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
    
    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true, false) as $error) {
            $errors[] = $error->getMessage();
        }
    
        foreach ($form->all() as $childForm) {
            if ($childErrors = $this->getFormErrors($childForm)) {
                $errors[$childForm->getName()] = $childErrors;
            }
        }
    
        return $errors;
    }
    
    #[Route('/addCategorie', name: 'app_add_categorie')]
public function addCategorie(Request $request): Response
{
    $cat = new Categorie();
    $form = $this->createForm(CategorieFormType::class, $cat);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cat);
        $entityManager->flush();

        // Redirect to a success page or return a success response
        return $this->redirectToRoute('app_artiste_listeCat');
    }

    // Render the form template with validation errors
    return $this->render('artiste/addcategorie.html.twig', [
        'f' => $form->createView(),
    ]);
}

    #[Route('/categorie/delete/{id}', name: 'app_categorie_delete')]
    public function deleteCat($id, ManagerRegistry $manager, CategorieRepository $repo)
    {
        $cat = $repo->find($id);
        $manager->getManager()->remove($cat);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_artiste_listeCat');
    }

    #[Route('/getCat', name: 'app_get_cat')]
    public function getCat(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $cat = $entityManager->getRepository(Categorie::class)->find($id);

        if (!$cat) {
            return new JsonResponse(['error' => 'Category not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $cat->getId(),
            'nom' => $cat->getNom(),
        ]);
    }

  
    
    
    #[Route('/updateCat/{id}', name: 'app_update_cat')]
    public function updateCat(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Categorie::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $category->setNom($request->request->get('nom')); 

        $entityManager->flush();

        return $this->redirectToRoute('app_artiste_listeCat');
    }
    





}
