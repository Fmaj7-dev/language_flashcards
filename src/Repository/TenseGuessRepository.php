<?php

namespace App\Repository;

use App\Entity\TenseGuess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Utils\VerbResult;
use App\Utils\Random;

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

    public function findOneOfTheWorsts($user_id, $lang_id)
    {
        $unknown_verbs = $this->getUnknownVerbs($user_id, $lang_id);

        // return $verb_result;
        $em = $this->getEntityManager();

        // query
        $query = 'SELECT T.value AS "value", TG.id AS "tense_guess_id", V.infinitive, TN.name AS "tense_name"
        FROM `tense` T, `tense_guess` TG, `verb` V, `tense_name` TN 
        WHERE T.id = TG.tense_id 
        AND T.verb_id = V.id 
        AND T.tensename_id = TN.id
        AND TG.user_id = :user
        AND TN.language_id = :lang
        
        ORDER BY TG.a2b_ok - TG.a2b_ko ASC
        LIMIT '.$unknown_verbs;

        $statement = $em->getConnection()->prepare($query);

        $statement->bindValue('user', $user_id);
        $statement->bindValue('lang', $lang_id);
        
        $statement->execute();
        $result = $statement->fetchAll();

        // check size
        if(count($result) == 0)
            return [];

        // choose one random result
        //$nth_element = rand(1, sizeof($result));
        $nth_element = Random::ExponentialDistribution(0.05, $unknown_verbs);
        $verb_result = new VerbResult( $result[$nth_element] );

        return $verb_result;
    }

    public function getUnknownVerbs($user_id, $lang_id)
    {
      $em = $this->getEntityManager();
      
      $query = 'SELECT count(TG.id) as count
      FROM `tense` T, `tense_guess` TG, `tense_name` TN 
      WHERE T.id = TG.tense_id 
      AND T.tensename_id = TN.id
      AND TG.user_id = :user
      AND TN.language_id = :lang';
      
      $statement = $em->getConnection()->prepare($query);
  
      $statement->bindValue('user', $user_id);
      $statement->bindValue('lang', $lang_id);
      $statement->execute();
  
      $result = $statement->fetchAll();
      return $result[0]["count"];
    }

    public function findOneOfTheUnknown($user_id, $lang_id)
    { 
        $em = $this->getEntityManager();

        // query
        $query = 'SELECT T.value AS "value", TG.id AS "tense_guess_id", V.infinitive, TN.name AS "tense_name"
        FROM `tense` T, `tense_guess` TG, `verb` V, `tense_name` TN 
        WHERE T.id = TG.tense_id 
        AND T.verb_id = V.id 
        AND T.tensename_id = TN.id
        AND TG.user_id = :user
        AND TN.language_id = :lang
        ORDER BY TG.a2b_ok + TG.a2b_ko ASC LIMIT 20';

        $statement = $em->getConnection()->prepare($query);

        $statement->bindValue('user', $user_id);
        $statement->bindValue('lang', $lang_id);
        
        $statement->execute();
        $result = $statement->fetchAll();

        // check size
        if(count($result) == 0)
            return [];

        // choose one random result
        $nth_element = rand(1, sizeof($result));
        $verb_result = new VerbResult( $result[$nth_element-1] );

        return $verb_result;
    }

    public function findOneRandom($user_id, $lang_id)
    {
        $em = $this->getEntityManager();

        // query
        $query = 'SELECT T.value AS "value", TG.id AS "tense_guess_id", V.infinitive, TN.name AS "tense_name"
        FROM `tense` T, `tense_guess` TG, `verb` V, `tense_name` TN 
        WHERE T.id = TG.tense_id 
        AND T.verb_id = V.id 
        AND T.tensename_id = TN.id
        AND TG.user_id = :user
        AND TN.language_id = :lang';

        $statement = $em->getConnection()->prepare($query);

        $statement->bindValue('user', $user_id);
        $statement->bindValue('lang', $lang_id);
        
        $statement->execute();
        $result = $statement->fetchAll();

        // check size
        if(count($result) == 0)
            return [];

        // choose one random result
        $nth_element = rand(1, sizeof($result));
        $verb_result = new VerbResult( $result[$nth_element-1] );

        return $verb_result;
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
