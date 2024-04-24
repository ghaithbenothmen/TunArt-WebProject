<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Oeuvre;

use App\Form\OeuvreType;
use App\Repository\OeuvreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/oeuvre')]
class OeuvreController extends AbstractController
{

    #[Route('/listeOeuvre', name: 'app_artiste_oeuvre')]
    public function listeOeuvre(OeuvreRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        // Get all oeuvres query
        $query = $repo->createQueryBuilder('o')
            ->getQuery();

        // Paginate the query results
        $oeuvres = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Get the page number from the request, default to 1
            6 // Number of items per page
        );

        return $this->render('oeuvre/oeuvre.html.twig', ['oeuvres' => $oeuvres]);
    }


    #[Route('/addOeuvre', name: 'app_add_oeuvre')]
    public function addOeuvre(Request $request, ManagerRegistry $manager): Response
    {
        $oeuvre = new Oeuvre();
        dump($oeuvre);
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);
   
        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement du fichier
            $file = $form['img']->getData();
            if ($file instanceof UploadedFile) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );
    
                $originalFileName = $file->getClientOriginalName();
             
                $oeuvre->setImg($fileName);
            }
            try {
                $entityManager = $manager->getManager();
                dump($oeuvre);
                $entityManager->persist($oeuvre);
                $entityManager->flush();

                return $this->redirectToRoute('app_artiste_oeuvre');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement.');
            }
        }
    
        // Le formulaire n'est pas valide, afficher les erreurs dans le template
        return $this->render('oeuvre/addoeuvre.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    

    #[Route('/update/{Id}', name: 'app_oeuvre_update')]
    public function updateOeuvre(ManagerRegistry $manager, $Id, OeuvreRepository $rep, Request $req)
    {
        $oeuvre = $rep->find($Id);
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($req);
        if ($form->isSubmitted()&& $form->isValid()) {
            $file = $form['img']->getData();
            if ($file instanceof UploadedFile) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );

                // Save the original file name in your entity
                $oeuvre->setImg($fileName);
            }

            $manager->getManager()->flush();
            return $this->redirectToRoute('app_artiste_oeuvre');
        }
        return $this->render('oeuvre/UpdateOeuvre.html.twig', ['f' => $form->createView()]); //create vue bch ybadel form l html ou renderForm
    }
    
 
}
