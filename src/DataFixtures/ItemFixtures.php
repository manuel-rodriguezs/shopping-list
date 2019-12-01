<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Service\StringUtils;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ItemFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $stringUtils = new StringUtils();

        $item = new Item();
        $item->setDescription('Milk');
        $item->setKey($stringUtils->removeAccentsAndLowerCase($item->getDescription()));

        $manager->persist($item);
        $manager->flush();

        $item = new Item();
        $item->setDescription('Apples');
        $item->setKey($stringUtils->removeAccentsAndLowerCase($item->getDescription()));

        $manager->persist($item);
        $manager->flush();

    }
}
