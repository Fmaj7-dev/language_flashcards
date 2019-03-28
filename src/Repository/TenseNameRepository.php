<?php

namespace App\Repository;

use App\Entity\TenseName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TenseName|null find($id, $lockMode = null, $lockVersion = null)
 * @method TenseName|null findOneBy(array $criteria, array $orderBy = null)
 * @method TenseName[]    findAll()
 * @method TenseName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenseNameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TenseName::class);
    }

    // /**
    //  * @return TenseName[] Returns an array of TenseName objects
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
    public function findOneBySomeField($value): ?TenseName
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
