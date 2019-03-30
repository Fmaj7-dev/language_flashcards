<?php

namespace App\Repository;

use App\Entity\TenseGuess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Utils\VerbResult;

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

    public function findOneOfTheWorsts()
    {
        $fake_result;
        $fake_result["tense_guess_id"] = "3";
        $fake_result["value"] = "mangeré";
        $fake_result["tense_name"] = "Future";
        $fake_result["infinitive"] = "Manger";

        $verb_result = new VerbResult( $fake_result );

        return $verb_result;
    }

    public function findOneOfTheUnknown()
    { 
    }

    public function findOneRandom()
    {
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
