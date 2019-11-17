<?php


namespace App\Service;
use App\Entity\Supermarket;


class ItemsService
{
    public function mergePricesWithItems(array $items, Supermarket $supermarket = null)
    {
        foreach ($items as $item)
            foreach ($supermarket->getPrices() as $price) {
                var_dump($price->getKey());
                die();
                if ($item->getKey() == $price->getKey())
                    $item->setPrice($price->getPrice());
            }

        return $items;
    }
}