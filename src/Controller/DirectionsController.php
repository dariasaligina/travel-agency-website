<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Form\ProductForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;



final class DirectionsController extends AbstractController
{
    #[Route('/directions', name: 'directions_index')]
    public function index(ProductRepository $repository ): Response
    {
        return $this->render('directions/index.html.twig', [
            'controller_name' => 'DirectionsController',
            'products' => $repository->findAll(),
        ]);
    }


    #[Route('/directions/{id<\d+>}', name:"product_show")]
    public function show(Product $product, ProductRepository $repository): Response
    {
        /*
        $product = $repository->findOneBy(['id'=> $id]);

        if ($product === null){
            throw $this->createNotFoundException("Product not found");
        }
    */
        return $this->render('directions/show.html.twig',['product'=>$product]);
    }


    #[Route('/product/new', name: 'product_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){
            $manager->persist($product);
            $manager->flush();

            $this->addFlash(
                "notice", "created successfully!"
            );
            
            return $this->redirectToRoute("product_show",['id'=> $product->getId()]);

        }


        return $this->render('directions/new.html.twig', ['form' => $form,]);
    }


    #[Route('/product/{id<\d+>}/edit', name: 'product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $manager) : Response {
        
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){
            
            $manager->flush();

            $this->addFlash(
                "notice", "updated successfully!"
            );
            
            return $this->redirectToRoute("product_show",['id'=> $product->getId()]);

        }
        return $this->render('directions/edit.html.twig', ['form' => $form,]);
        
    }

    #[Route('/product/{id<\d+>}/delete', name: 'product_delete')]
    public function delete(Product $product, Request $request, EntityManagerInterface $manager) : Response{

        if ($request->isMethod('POST')){
            $manager->remove($product);
            $manager->flush();
            $this->addFlash(
                'notice',
                "product deleted"
            );
            return $this->redirectToRoute("directions_index");
        }
        return $this->render('directions/delete.html.twig', ['id'=> $product->getId(),]);

    }
}
