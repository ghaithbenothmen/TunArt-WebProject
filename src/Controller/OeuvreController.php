<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;
use App\Entity\Oeuvre;
use App\Entity\Like;
use App\Entity\User;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
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
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use App\Entity\OeuvreNotification;





class OeuvreController extends AbstractController
{

    #[Route('/listeOeuvre', name: 'app_artiste_oeuvre')]
public function listeOeuvre(OeuvreRepository $repo, PaginatorInterface $paginator, Request $request): Response
{
    // Get the type parameter from the request
    $type = $request->query->get('typeoeuvre');

    // Get all oeuvres query
    $query = $repo->createQueryBuilder('o');

    // Filter by type if the type parameter is provided
    if ($type && $type !== 'all') {
        $query->andWhere('o.typeoeuvre = :type')
              ->setParameter('type', $type);
    }

    $query = $query->getQuery();

    // Paginate the query results
    $oeuvres = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), // Get the page number from the request, default to 1
        6 // Number of items per page
    );

    return $this->render('app/listoeuvres.html.twig', ['oeuvres' => $oeuvres]);
}
#[Route('/artiste/listeOeuvre', name: 'app_oeuvre')]
public function listeOeuvre1(OeuvreRepository $repo, PaginatorInterface $paginator, Request $request, Security $security): Response
{
    // Get the currently logged-in user
    $user = $security->getUser();

    // Check if a user is logged in
    if (!$user) {
        // Redirect or show an error message
        // For example, return a 403 Forbidden response
        return new Response('You are not authorized to view this page.', Response::HTTP_FORBIDDEN);
    }

    // Get all oeuvres associated with the currently logged-in user
    $oeuvres = $repo->findByArtiste($user);

    // Paginate the query results
    $oeuvres = $paginator->paginate(
        $oeuvres,
        $request->query->getInt('page', 1), // Get the page number from the request, default to 1
        3 // Number of items per page
    );

    return $this->render('oeuvre/oeuvre.html.twig', ['oeuvres' => $oeuvres]);
}


    
    #[Route('/artiste/addOeuvre', name: 'app_add_oeuvre')]
    public function addOeuvre(Request $request, ManagerRegistry $manager,NotifierInterface $notifier): Response
    {
        $oeuvre = new Oeuvre();
        $user = $this->getUser();
       $oeuvre->setArtisteId($user);
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
                
                
                
                $entityManager->persist($oeuvre); // Ensure this line is after persisting the notification
                $entityManager->flush();
            
                
            
                return $this->redirectToRoute('app_oeuvre');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement.');
            }
        }
    
        // Le formulaire n'est pas valide, afficher les erreurs dans le template
        return $this->render('oeuvre/addoeuvre.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    

    #[Route('/artiste/update/{Id}', name: 'app_oeuvre_update')]
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
            return $this->redirectToRoute('app_oeuvre');
        }
        return $this->render('oeuvre/UpdateOeuvre.html.twig', ['f' => $form->createView()]); //create vue bch ybadel form l html ou renderForm
    }

    
    #[Route('/artiste/oeuvre/delete/{id}', name: 'app_oeuvre_delete')]
    public function deleteOeuvre($id, ManagerRegistry $manager, OeuvreRepository $repo)
    {
        $oeuvre = $repo->find($id);
        $manager->getManager()->remove($oeuvre);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_oeuvre');
    }

    #[Route('/search/oeuvre', name: 'app_search_oeuvre')]
    public function searchOeuvre(Request $request, OeuvreRepository $repo): JsonResponse
    {
        $searchText = $request->query->get('q');
        $oeuvres = $repo->findBySearchText($searchText); // Implement a custom method in your repository to fetch oeuvres based on search query
    
        // Serialize the oeuvres to JSON and return as response
        $serializedOeuvres = $this->serializeOeuvres($oeuvres); // Implement serialization logic
        return new JsonResponse($serializedOeuvres);
    }
    
    // Implement serialization logic to convert oeuvres to JSON format
    private function serializeOeuvres($oeuvres): array
    {
        // Implement your serialization logic here
        // Example: Convert oeuvres array to associative array or JSON string
        return $oeuvres; // Return serialized oeuvres
    }



    #[Route('/artiste/oeuvre/qrcode/{id}', name: 'oeuvre_qrcode')]
public function generateOeuvreQrCode(int $id): Response
{
    $oeuvre = $this->getDoctrine()->getRepository(Oeuvre::class)->find($id);
    
    if (!$oeuvre) {
        throw $this->createNotFoundException('Oeuvre introuvable');
    }
    
    $qrCodeContent = sprintf(
        "Nom de l'oeuvre : %s\nType de l'oeuvre : %s\nDescription : %s\nDate de publication : %s\nNote : %d\nArtiste : %s\nImage : %s",
        $oeuvre->getNomOeuvre(),
        $oeuvre->getTypeOeuvre(),
        $oeuvre->getDescription(),
        $oeuvre->getDatePublication()->format('Y-m-d'), // Format the date as needed
        $oeuvre->getNote(),
        $oeuvre->getArtisteId()->getNom(),
        $this->getParameter('kernel.project_dir') . '/public/uploads/' . $oeuvre->getImg(),
        // Adjust the path to match the location of your images
    );
    
    // Create the QR code with the content
    $qrCode = QrCode::create($qrCodeContent)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
        ->setSize(300) // Taille du QR code
        ->setMargin(10) // Marge autour du QR code
        ->setForegroundColor(new Color(0, 0, 0)) // Couleur du QR code
        ->setBackgroundColor(new Color(255, 255, 255)); // Couleur de fond du QR code

    // Optionally, you can add a logo to the QR code
    // $logo = Logo::create('path_to_your_logo.png')->setResizeToWidth(60);

    // Create a label for the QR code
    $label = Label::create('')
        ->setFont(new NotoSans(12)); // Police de caractères de l'étiquette

    // Generate the QR code image with the logo and label
    $writer = new PngWriter();
    $qrCodeImage = $writer->write(
        $qrCode,
        null,
        $label->setText('Scanner ici!!') // Texte de l'étiquette
    )->getDataUri(); // Obtenir les données de l'image au format URI

    // Pass the QR code image data to the Twig template for display
    return $this->render('oeuvre/qrcode.html.twig', [
        'qrCodeImage' => $qrCodeImage,
    ]);
}


#[Route('/filterByType', name: 'app_filter_oeuvre_by_type')]
public function filterOeuvreByType(Request $request, OeuvreRepository $repo): Response
{
    $type = $request->query->get('type');
    $oeuvres = $repo->findByType($type); // Implement this method in your repository

    // Serialize the filtered oeuvres to JSON and return as response
    $serializedOeuvres = $this->serializeOeuvres($oeuvres);
    return new JsonResponse($serializedOeuvres);
}



#[Route('/like-dislike', name: 'app_like_dislike_oeuvre')]
public function likeDislikeOeuvre(Request $request, ManagerRegistry $manager): Response
{
    $oeuvreId = $request->request->get('oeuvreId');
    $isLike = $request->request->get('isLike');

    // Handle user ID generation randomly (replace with your logic)
    $userId = $this->getUser()->getUserIdentifier(); // Generate random user ID between 1 and 100
    
    // Save like/dislike to the database
    $like = new Like();
    $like->setIdUser($userId);
    $like->setIdOeuvre($oeuvreId);
    $like->setEtat($isLike);
    $like->setLikecount(1); // Assuming you want to count each like/dislike separately

    $entityManager = $manager->getManager();
    $entityManager->persist($like);
    $entityManager->flush();

    // Return a success response
    return new Response('Success', Response::HTTP_OK);
}
#[Route('/oeuvres/{artisteId}', name: "oeuvre_by_artiste")]
public function showOeuvresByArtiste(OeuvreRepository $repo, $artisteId, PaginatorInterface $paginator, Request $request)
{

    $type = $request->query->get('typeoeuvre');

    // Get all oeuvres query
    $queryBuilder = $repo->createQueryBuilder('o');

    // Filter by type if the type parameter is provided
    if ($type && $type !== 'all') {
        $queryBuilder->andWhere('o.typeoeuvre = :type')
              ->setParameter('type', $type);
    }

    $query = $queryBuilder->getQuery();
    // Fetch oeuvres by artiste ID
    $oeuvres = $query->getResult();

    // Fetch artiste information
    $artiste = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($artisteId);

    // Paginate the query results
    $oeuvres = $paginator->paginate(
        $oeuvres,
        $request->query->getInt('page', 1), // Get the page number from the request, default to 1
        3 // Number of items per page
    );

    // Render the template with the fetched oeuvres and artiste information
    return $this->render('app/listoeuvres.html.twig', [
        'oeuvres' => $oeuvres,
        'artiste' => $artiste, // Pass artiste information to the template
    ]);
}




}
