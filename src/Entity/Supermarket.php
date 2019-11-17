<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupermarketRepository")
 */
class Supermarket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[\w\d\s çÇñÑáéíóúÁÉÍÓÚ]*$/", message="You are using not allowed characters.")
     * @Assert\LessThanOrEqual(20)
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Price", mappedBy="supermarket")
     */
    private $prices;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrices(): ?Collection
    {
        return $this->prices;
    }

    public function getAveragePrice(): string
    {
        $average = 0;

        if (count($this->prices) > 0) {
            foreach ($this->prices as $price) $average += $price->getPrice();

            $average = $average / count($this->prices);
        }

        return money_format('%.2n', $average);
    }

    public function getCheaperItem(): string
    {
        $cheaper = null;

        foreach ($this->prices as $price) {
            if ($cheaper === null) $cheaper = $price;
            if ($cheaper->getPrice() > $price->getPrice()) $cheaper = $price;
        }

        return ($cheaper) ? $cheaper->getKey() : "";
    }
}
