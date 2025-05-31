<?php

namespace App\Controller;

use App\Entity\RoutePhoto;
use App\Form\RoutePhotoForm;
use App\Repository\RoutePhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface; // Import SluggerInterface

#[Route('/route/photo')]
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
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $routePhoto = new RoutePhoto();
        $form = $this->createForm(RoutePhotoForm::class, $routePhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo')->getData(); // Get the uploaded file

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Latin-ASCII', $originalFilename); // Use SluggerInterface
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $this->addFlash('error', 'Failed to upload the image. Please try again.');
                    return $this->redirectToRoute('app_route_photo_new');
                }

                $routePhoto->setPhoto($newFilename); // Set the filename in the entity
            }

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
    public function edit(Request $request, RoutePhoto $routePhoto, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(RoutePhotoForm::class, $routePhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo')->getData(); // Get the uploaded file

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename); // Use SluggerInterface
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $this->addFlash('error', 'Failed to upload the image. Please try again.');
                    return $this->redirectToRoute('app_route_photo_edit', ['id' => $routePhoto->getId()]);
                }

                $routePhoto->setPhoto($newFilename); // Set the filename in the entity
            }
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
        if ($this->isCsrfTokenValid('delete'.$routePhoto->getId(), $request->request->get('_token'))) {
            $entityManager->remove($routePhoto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_route_photo_index', [], Response::HTTP_SEE_OTHER);
    }
}