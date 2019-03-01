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

    public function findOneOfTheWorsts($n, $langQuery)
    {   
      $orderBy = '';
      if($langQuery == 'langA')
        $orderBy = 'w.a2bOk - w.a2bKo';
      if($langQuery == 'langB')
        $orderBy = 'w.b2aOk - w.b2aKo';

      $qb = $this->createQueryBuilder('w')
                 ->orderBy($orderBy, 'ASC')
                 ->setMaxResults($n)
                 ->getQuery();

      $result = $qb->execute();

      $nth_element = rand(1, sizeof($result));

      return $result[$nth_element - 1];
    }

    public function findOneOfTheUnknown($n, $langQuery)
    {   
      $orderBy = '';
      if($langQuery == 'langA')
        $orderBy = 'w.a2bOk + w.a2bKo';
      if($langQuery == 'langB')
        $orderBy = 'w.b2aOk + w.b2aKo';

      $qb = $this->createQueryBuilder('w')
                 ->orderBy($orderBy, 'DESC')
                 ->setMaxResults($n)
                 ->getQuery();

      $result = $qb->execute();

      $nth_element = rand(1, sizeof($result));

      return $result[$nth_element - 1];
    }

    public function findOneRandom()
    {
      $qb = $this->createQueryBuilder('c')
                 ->select('count(c.id)')
                 ->getQuery();
      
      $count = $qb->execute();

      $offset = rand( 0, $count[0][1]-1 );

      $qb = $this->createQueryBuilder('w')
      ->setMaxResults(1)
      ->setFirstResult($offset)
      ->getQuery();

      $result = $qb->execute();

      $nth_element = rand(1, sizeof($result));

      return $result[$nth_element - 1];
    }
}
