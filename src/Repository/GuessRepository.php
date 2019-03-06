<?php

namespace App\Repository;

use App\Entity\Guess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\Expr\Join;

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
    dump($langQuery);
    //$orderBy = '';
    if($langQuery == 'langA')
      $orderBy = 'w.a2bOk - w.a2bKo';
    if($langQuery == 'langB')
      $orderBy = 'w.b2aOk - w.b2aKo';

    $qb = $this->createQueryBuilder('w')
                ->orderBy($orderBy, 'ASC')
                ->setMaxResults($n)
                ->where('w.user = 1')
                ->getQuery();

    $result = $qb->execute();

    $nth_element = rand(1, sizeof($result));

    return $result[$nth_element - 1];
  }

  public function findOneOfTheUnknown($n, $langQuery)
  {   
    //$orderBy = '';
    if($langQuery == 'langA')
      $orderBy = 'w.a2bOk + w.a2bKo';
    if($langQuery == 'langB')
      $orderBy = 'w.b2aOk + w.b2aKo';

    $qb = $this->createQueryBuilder('w')
                ->orderBy($orderBy, 'ASC')
                ->setMaxResults($n)
                ->where('w.user = 1')
                ->getQuery();

    $result = $qb->execute();

    $nth_element = rand(1, sizeof($result));

    return $result[$nth_element - 1];
  }

  public function findOneRandom()
  {
    $qb = $this->createQueryBuilder('c')
                ->select('count(c.id)')
                ->where('c.user = 1')
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

  // select count(guess.id) from guess, vocabulary 
  // where guess.vocabulary_id = vocabulary.id 
  // and guess.user_id = 1 
  // and vocabulary.language_a = 2
  public function getCount()
  {
    $qb = $this->createQueryBuilder('g');

    /*$qb->select('g')
          ->innerJoin('vocabulary', 'v', Join::WITH, $qb->expr()->andx(
              $qb->expr()->eq('v.id', 'g.vocabulary_id'),
              $qb->expr()->eq('v.language_a', ':language')
          ))
          ->and('g.user_id = :user_id');*/

    /*$qb->select('g')
    ->join('vocabulary', 'v')
    ->where('g.vocabulary.id = v.id')
    ->andWhere('g.user_id = :user')
    ->andWhere('v.language_a = :lang');

    $qb->setParameters(array(
            'user' => 1,
            'lang' => 2,
        ));
    
    $query = $qb->getQuery();
    dump($query);
    return $query->getResult();

    return $count[0][1];*/

    $em = $this->getEntityManager();
    
    $query = 'select count(guess.id) as count from guess, vocabulary  where guess.vocabulary_id = vocabulary.id and guess.user_id = :user  and vocabulary.language_a = :lang';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', 1);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    return $result[0]["count"];
  }

  public function getKnownA()
  {
    $em = $this->getEntityManager();
    
    $query = 'select count(guess.id) as count from guess, vocabulary  
    where guess.vocabulary_id = vocabulary.id 
    and guess.a2b_ok > guess.a2b_ko
    and guess.user_id = :user  
    and vocabulary.language_a = :lang';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', 1);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    return $result[0]["count"];
  }

  public function getKnownB()
  {
    $em = $this->getEntityManager();
    
    $query = 'select count(guess.id) as count from guess, vocabulary  
    where guess.vocabulary_id = vocabulary.id 
    and guess.b2a_ok > guess.b2a_ko
    and guess.user_id = :user  
    and vocabulary.language_a = :lang';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', 1);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    if(count($result) == 0)
      return [];

    return $result[0]["count"];
  }

  public function getWorstA2B()
  {
    $em = $this->getEntityManager();
    
    $query = 'select v.word_a, v.word_b, g.a2b_ok, g.a2b_ko, g.a2b_ok-g.a2b_ko from guess g, vocabulary v 
    where g.a2b_ok-g.a2b_ko < 0 
    and g.vocabulary_id = v.id 
    and g.user_id = 1 
    and v.language_a = 2';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', 1);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    if(count($result) == 0)
      return [];

    return $result[0];
  }

  public function getWorstB2A()
  {
    $em = $this->getEntityManager();
    
    $query = 'select v.word_b, v.word_a, g.b2a_ok, g.b2a_ko, g.b2a_ok-g.b2a_ko from guess g, vocabulary v 
    where g.b2a_ok-g.b2a_ko < 0 
    and g.vocabulary_id = v.id 
    and g.user_id = 1 
    and v.language_a = 2';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', 1);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();

    return $result[0];
  }

}
