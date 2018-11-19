<?php

namespace App\Repository;

use App\Entity\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }

    /**
     * @param $authToken
     * @return UserEntity|null
     */
    public function findByAuthToken($authToken)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.authToken = :auth_token')
            ->setParameter('auth_token', $authToken)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
