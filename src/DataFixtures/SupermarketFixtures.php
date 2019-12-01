<?php

namespace App\DataFixtures;

use App\Entity\Supermarket;
use App\Entity\Price;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SupermarketFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $supermarket = new Supermarket();
        $supermarket->setName('Carrefour');

        $manager->persist($supermarket);
        $manager->flush();

        $price = new Price();
        $price->setSupermarket($supermarket);
        $price->setKey('carrots');
        $price->setPrice(0.8);

        $manager->persist($price);
        $manager->flush();

        $price = new Price();
        $price->setSupermarket($supermarket);
        $price->setKey('apples');
        $price->setPrice(0.9);

        $manager->persist($price);
        $manager->flush();

    }
}
