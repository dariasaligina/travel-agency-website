<?php

namespace App\Controller;

use App\Entity\Route as rt;
use App\Form\Route1Form;
use App\Repository\RouteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rt')]
final class RtController extends AbstractController
{
    #[Route(name: 'app_rt_index', methods: ['GET'])]
    public function index(RouteRepository $routeRepository): Response
    {
        return $this->render('rt/index.html.twig', [
            'routes' => $routeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $route = new rt();
        $form = $this->createForm(Route1Form::class, $route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($route);
            $entityManager->flush();

            return $this->redirectToRoute('app_rt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rt/new.html.twig', [
            'route' => $route,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rt_show', methods: ['GET'])]
    public function show(rt $route): Response
    {
        return $this->render('rt/show.html.twig', [
            'route' => $route,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, rt $route, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Route1Form::class, $route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rt/edit.html.twig', [
            'route' => $route,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rt_delete', methods: ['POST'])]
    public function delete(Request $request, rt $route, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$route->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($route);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rt_index', [], Response::HTTP_SEE_OTHER);
    }
}
