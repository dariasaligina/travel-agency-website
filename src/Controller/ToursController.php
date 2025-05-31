<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ToursController extends AbstractController
{
    #[Route('/tours/tour', name: 'tour_description')]
    public function description(): Response
    {
        return $this->render('tours/tour.html.twig', [
            'controller_name' => 'ToursController',
        ]);
    }

    #[Route('/tours/catalog', name: 'tour_catalog')]
    public function catalog(): Response
    {
        return $this->render('tours/catalog.html.twig', [
            'controller_name' => 'ToursController',
        ]);
    }
}
