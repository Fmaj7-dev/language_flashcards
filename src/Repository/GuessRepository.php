<?php

namespace App\Repository;

use App\Entity\Guess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 */
class GuessRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guess::class);
    }
    public function findOneOfTheWorsts($n)
    {   
      $qb = $this->createQueryBuilder('w')
                 ->orderBy('w.a2bOk - w.a2bKo', 'ASC')
                 ->setMaxResults($n)
                 ->getQuery();

      $result = $qb->execute();

      $nth_element = rand(1, sizeof($result));

      return $result[$nth_element - 1];
    }
}
