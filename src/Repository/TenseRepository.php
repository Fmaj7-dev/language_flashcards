<?php

namespace App\Repository;

use App\Entity\Tense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tense[]    findAll()
 * @method Tense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tense::class);
    }

    // /**
    //  * @return Tense[] Returns an array of Tense objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tense
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
