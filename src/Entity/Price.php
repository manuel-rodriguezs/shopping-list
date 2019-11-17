<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PriceRepository")
 */
class Price
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
     * @Assert\LessThanOrEqual(30)
     * @ORM\Column(type="string", length=30)
     */
    private $key;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Supermarket", inversedBy="prices", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="supermarket_id", referencedColumnName="id")
     */
    private $supermarket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getPrice(): ?string
    {
        return money_format('%.2n', $this->price);
    }

    public function getSupermarket()
    {
        $supermarket = new class() {
            private $id;
            private $name;

            public function setId ($id): void {
                $this->id = $id;
            }

            public function getId (): int {
                return $this->id;
            }

            public function setName ($name): void {
                $this->name = $name;
            }

            public function getName (): string {
                return $this->name;
            }
        };
        $supermarket->setId($this->supermarket->getId());
        $supermarket->setName($this->supermarket->getName());

        return $supermarket;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function setSupermarket(Supermarket $supermarket): self
    {
        $this->supermarket = $supermarket;

        return $this;
    }
}
