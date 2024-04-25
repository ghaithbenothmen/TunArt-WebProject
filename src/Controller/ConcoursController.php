<?php

namespace App\Controller;

use DateTime;
use App\Repository\ConcoursRepository;
use App\Entity\Concours;
use App\Form\ConcoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/concours')]
class ConcoursController extends AbstractController
{
    #[Route('/', name: 'app_concours_index', methods: ['GET'])]
    public function index(ConcoursRepository $concoursRepository): Response
    {
        return $this->render('concours/index.html.twig', [
            'concours' => $concoursRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_concours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentDate = new DateTime();
        $concour = new Concours($currentDate);
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($concour);
            $entityManager->flush();

            return $this->redirectToRoute('app_concours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('concours/new.html.twig', [
            'concour' => $concour,
            'form' => $form,
        ]);
    }

    #[Route('/{refrence}', name: 'app_concours_show', methods: ['GET'])]
    public function show(Concours $concour): Response
    {
        return $this->render('concours/show.html.twig', [
            'concour' => $concour,
        ]);
    }

    #[Route('/{refrence}/edit', name: 'app_concours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Concours $concour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_concours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('concours/edit.html.twig', [
            'concour' => $concour,
            'form' => $form,
        ]);
    }

    #[Route('/{refrence}', name: 'app_concours_delete', methods: ['POST'])]
    public function delete(Request $request, Concours $concour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$concour->getRefrence(), $request->request->get('_token'))) {
            $entityManager->remove($concour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_concours_index', [], Response::HTTP_SEE_OTHER);
    }
}
