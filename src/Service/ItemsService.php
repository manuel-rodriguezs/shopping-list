<?php


namespace App\Service;
use App\Entity\Supermarket;


class ItemsService
{
    public function mergePricesWithItems(array $items, Supermarket $supermarket = null)
    {
        foreach ($items as $item)
            foreach ($supermarket->getPrices() as $price) {
                if ($item->getKey() == $price->getKey())
                    $item->setPrice($price->getPrice());
            }

        return $items;
    }
}