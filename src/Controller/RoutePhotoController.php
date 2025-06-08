<?php

namespace App\Controller;

use App\Entity\RoutePhoto;
use App\Form\RoutePhotoForm;
use App\Repository\RoutePhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/route-photo')]
final class RoutePhotoController extends AbstractController
{
    #[Route(name: 'app_route_photo_index', methods: ['GET'])]
    public function index(RoutePhotoRepository $routePhotoRepository): Response
    {
        return $this->render('route_photo/index.html.twig', [
            'route_photos' => $routePhotoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_route_photo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $routePhoto = new RoutePhoto();
        $form = $this->createForm(RoutePhotoForm::class, $routePhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($routePhoto);
            $entityManager->flush();

            return $this->redirectToRoute('app_route_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('route_photo/new.html.twig', [
            'route_photo' => $routePhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_route_photo_show', methods: ['GET'])]
    public function show(RoutePhoto $routePhoto): Response
    {
        return $this->render('route_photo/show.html.twig', [
            'route_photo' => $routePhoto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_route_photo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RoutePhoto $routePhoto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RoutePhotoForm::class, $routePhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_route_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('route_photo/edit.html.twig', [
            'route_photo' => $routePhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_route_photo_delete', methods: ['POST'])]
    public function delete(Request $request, RoutePhoto $routePhoto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$routePhoto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($routePhoto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_route_photo_index', [], Response::HTTP_SEE_OTHER);
    }
}
