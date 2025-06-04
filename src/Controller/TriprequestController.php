<?php

namespace App\Controller;

use App\Entity\Triprequest;
use App\Form\TriprequestForm;
use App\Repository\TriprequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route as rt;

#[rt('/triprequest')]
final class TriprequestController extends AbstractController
{
    #[rt(name: 'app_triprequest_index', methods: ['GET'])]
    public function index(TriprequestRepository $triprequestRepository): Response
    {
        return $this->render('triprequest/index.html.twig', [
            'triprequests' => $triprequestRepository->findAll(),
        ]);
    }

    #[rt('/new', name: 'app_triprequest_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $triprequest = new Triprequest();
        $form = $this->createForm(TriprequestForm::class, $triprequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($triprequest);
            $entityManager->flush();

            return $this->redirectToRoute('app_triprequest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('triprequest/new.html.twig', [
            'triprequest' => $triprequest,
            'form' => $form,
        ]);
    }

    #[rt('/{id}', name: 'app_triprequest_show', methods: ['GET'])]
    public function show(Triprequest $triprequest): Response
    {
        return $this->render('triprequest/show.html.twig', [
            'triprequest' => $triprequest,
        ]);
    }

    #[rt('/{id}/edit', name: 'app_triprequest_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Triprequest $triprequest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TriprequestForm::class, $triprequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_triprequest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('triprequest/edit.html.twig', [
            'triprequest' => $triprequest,
            'form' => $form,
        ]);
    }

    #[rt('/{id}', name: 'app_triprequest_delete', methods: ['POST'])]
    public function delete(Request $request, Triprequest $triprequest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$triprequest->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($triprequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_triprequest_index', [], Response::HTTP_SEE_OTHER);
    }
}
