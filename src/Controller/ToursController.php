<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Route as RouteEntity;
use App\Entity\Triprequest;
use App\Repository\CityRepository;
use App\Repository\RouteRepository;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TriprequestForm;
use Doctrine\ORM\EntityManagerInterface;

final class ToursController extends AbstractController
{
    #[Route('route/{id<\d+>}', name: 'tour_description')]
    public function description(RouteEntity $route, Request $request, EntityManagerInterface $entityManager): Response
    {
        $triprequest = new Triprequest();
        $form = $this->createForm(TriprequestForm::class, $triprequest, [
            'route' => $route, 
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $submittedTriprequest = $form->getData();
            $submittedTriprequest->setActive(True);
            $submittedTriprequest->setProcessed(false);
            $entityManager->persist($submittedTriprequest);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Ваш запрос успешно отправлен! В ближайшее время с вами свяжется наш сотрудник.'
            );
            return $this->redirectToRoute('tour_description', ['id' => $route->getId()]);
        }

        return $this->render('tours/tour.html.twig', [
            'controller_name' => 'ToursController',
            "route"=> $route,
            "form"=> $form->createView(),
        ]);
    }

    #[Route('catalog', name: 'tour_catalog')]
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

    #[Route("", name: "main")]
    public function main(){
        return $this->render("tours/index.html.twig");
    }
    #[Route("about", name:"about")]
    public function about(){
        return $this->render("tours/about.html.twig");
    }
    #[Route("admin", name:"admin")]
    public function admin(){
        return $this->render("tours/admin.html.twig");
    }
}
