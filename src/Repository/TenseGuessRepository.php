<?php

namespace App\Repository;

use App\Entity\TenseGuess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TenseGuess|null find($id, $lockMode = null, $lockVersion = null)
 * @method TenseGuess|null findOneBy(array $criteria, array $orderBy = null)
 * @method TenseGuess[]    findAll()
 * @method TenseGuess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenseGuessRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TenseGuess::class);
    }

    // /**
    //  * @return TenseGuess[] Returns an array of TenseGuess objects
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
    public function findOneBySomeField($value): ?TenseGuess
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
