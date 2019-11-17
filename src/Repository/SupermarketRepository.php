<?php

namespace App\Repository;

use App\Entity\Supermarket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Supermarket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supermarket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supermarket[]    findAll()
 * @method Supermarket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupermarketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Supermarket::class);
    }

    public function save(Supermarket $supermarket)
    {
        $this->getEntityManager()->persist($supermarket);
        $this->getEntityManager()->flush();
    }

    public function remove(Supermarket $supermarket)
    {
        foreach($supermarket->getPrices() as $price)
            $this->getEntityManager()->remove($price);

        $this->getEntityManager()->remove($supermarket);
        $this->getEntityManager()->flush();
    }
}
