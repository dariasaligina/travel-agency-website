<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product;
        $product->setName("Product 1");
        $product->setDescription("deafjalkk; adkfjj;");
        $product->setSize(100);

        $manager->persist($product);
        $product = new Product;
        $product->setName("Product 2");
        $product->setDescription("dea          fjalkk; adkfjj;");
        $product->setSize(300);
        $manager->persist($product);

        $manager->flush();
    }
}
