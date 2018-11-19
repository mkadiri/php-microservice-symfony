<?php

namespace App\Repository;

use App\Entity\AdEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AdEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdEntity::class);
    }

    /**
     * Returns ads ordered by the latest creation date
     *
     * @return AdEntity[]|null
     */
    public function findAllOrderByLatest()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
