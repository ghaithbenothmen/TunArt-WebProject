<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formation')]
class FormationController extends AbstractController
{

    
#[Route('/get-formations-as-events', name: 'app_get_formations_as_events')]
public function getFormationsAsEvents(FormationRepository $formationRepository): JsonResponse
{
    $formations = $formationRepository->findAll();

    $events = [];
    foreach ($formations as $formation) {
        $events[] = [
            'title' => $formation->getNom(),
            'start' => $formation->getDatedebut()->format('Y-m-d'),
            'end' => $formation->getDatefin()->format('Y-m-d'),
        ];
    }

    return new JsonResponse($events);
}

    #[Route('/listeFor', name: 'app_artiste_formation')]
    public function listeFor(PaginatorInterface $paginator, Request $request, FormationRepository $repo, CategorieRepository $categorieRepo): Response
    {
        $term = $request->query->get('q');
        $categorieId = $request->query->get('categorie');
        
        $queryBuilder = $repo->createQueryBuilder('f');
    
        if ($term) {
            $queryBuilder->andWhere('f.nom LIKE :term')
                       ->setParameter('term', '%'.$term.'%');
        }
    
        if ($categorieId) {
            $categorie = $categorieRepo->find($categorieId);
            if ($categorie) {
                $queryBuilder->andWhere('f.cat = :categorie')
                           ->setParameter('categorie', $categorie);
            }
        }
    
        $query = $queryBuilder->getQuery();
    
        $formations = $paginator->paginate(
            $query, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de page par défaut
            4 // Nombre d'éléments par page
        );
    
        $categories = $categorieRepo->findAll();
       // $formations = $query->getResult();
        
        return $this->render('formation/formation.html.twig', [
            
            
            'categories' => $categories,'formations' => $formations
        ]);
    }
    
    


    #[Route('/addFormation', name: 'app_add_formation')]
    public function addFormation(Request $request, ManagerRegistry $manager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement du fichier
            $file = $form['image']->getData();
            if ($file instanceof UploadedFile) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );
    
                $originalFileName = $file->getClientOriginalName();
             
                $formation->setImage($fileName);
            }
            try {
                $entityManager = $manager->getManager();
                $entityManager->persist($formation);
                $entityManager->flush();

                return $this->redirectToRoute('app_artiste_formation');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement.');
            }
        }
    
        // Le formulaire n'est pas valide, afficher les erreurs dans le template
        return $this->render('formation/addformation.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    
    #[Route('/formation/delete/{id}', name: 'app_formation_delete')]
    public function deleteCat($id, ManagerRegistry $manager, FormationRepository $repo)
    {
        $cat = $repo->find($id);
        $manager->getManager()->remove($cat);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_artiste_formation');
    }

    #[Route('/update/{id}', name: 'app_formation_update')]
    public function updateFormation(ManagerRegistry $manager, $id, FormationRepository $rep, Request $req)
    {
        $formation = $rep->find($id);
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($req);
        if ($form->isSubmitted()&& $form->isValid()) {
            $file = $form['image']->getData();
            if ($file instanceof UploadedFile) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );

                // Save the original file name in your entity
                $formation->setImage($fileName);
            }

            $manager->getManager()->flush();
            return $this->redirectToRoute('app_artiste_formation');
        }
        return $this->render('formation/updatefor.html.twig', ['f' => $form->createView()]); //create vue bch ybadel form l html ou renderForm
    }
    
 
    
}
