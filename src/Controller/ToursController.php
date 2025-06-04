<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Route as Rt;
use App\Entity\Triprequest;
use App\Repository\CityRepository;
use App\Repository\RouteRepository;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TriprequestForm;
use Doctrine\ORM\EntityManagerInterface;

final class ToursController extends AbstractController
{
    #[Route('/tours/route/{id<\d+>}', name: 'tour_description')]
    public function description(Rt $route, Request $request, EntityManagerInterface $entityManager): Response
    {
        $triprequest = new Triprequest();
        // Возможно, вам нужно будет установить route_id для Triprequest, если это необходимо для вашей логики
        // $triprequest->setRoute($route);
        $form = $this->createForm(TriprequestForm::class, $triprequest, [
            'route' => $route, // <--- Передаем объект Route в опции формы
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Process the form data
            // ...
            // Redirect or render a success page
            // return $this->redirectToRoute('...');
            $submittedTriprequest = $form->getData();
            $submittedTriprequest->setActive(True);
            $submittedTriprequest->setProcessed(false);
            

            // Здесь вы можете выполнить любую логику обработки:
            // 1. Сохранить данные в базе данных:
            $entityManager->persist($submittedTriprequest);
            $entityManager->flush();

            $this->addFlash(
                'success', // Тип сообщения (можно использовать 'success', 'warning', 'danger', 'info')
                'Ваш запрос успешно отправлен! В ближайшее время с вами свяжется наш сотрудник.' // Текст сообщения
            );
            return $this->redirectToRoute('tour_description', ['id' => $route->getId()]);
        }

        return $this->render('tours/tour.html.twig', [
            'controller_name' => 'ToursController',
            "route"=> $route,
            "form"=> $form->createView(),
        ]);
    }

    #[Route('/tours/catalog', name: 'tour_catalog')]
    public function catalog(Request $request, RouteRepository $route_repository, CityRepository $city_repository): Response
    {
        $filters=[];
        $request_value = [
        'start_date' => $request->query->get('start_date'),
        'end_date' => $request->query->get('end_date'),
        'destination' => $request->query->get('destination'),
        'departure' => $request->query->get('departure'),
        'max_price' => $request->query->get('max-price')
    ];
        if ($request_value["destination"]){
            $destinationCity = $city_repository->find($request_value["destination"]);
        if ($destinationCity) {
            $filters["direction"] = $destinationCity;
        }
        }
        if ($request_value['departure']){
            $departureCity = $city_repository->find($request_value['departure']);
        if ($departureCity) {
            $filters["departure_city"] = $departureCity;
        }
        }
        $routs = 
        $route_repository->findRoutesWithFilter(
        $request_value['start_date']? new \DateTime($request_value['start_date']) : null,
        $request_value['end_date']? new \DateTime($request_value["end_date"]) : null, 
        intval($request_value["destination"]), 
        intval($request_value["departure"]),
        $request_value["max_price"]? intval($request_value["max_price"]):null);
        
        $cities = $city_repository->findAll();
        return $this->render('tours/catalog.html.twig', [
            'controller_name' => 'ToursController',
            "routs"=>$routs,
            "cities"=>$cities,
            "request_value"=>$request_value,
        ]);
    }
}
