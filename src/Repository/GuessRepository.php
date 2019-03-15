<?php

namespace App\Repository;

use App\Entity\Guess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\Expr\Join;

use App\Utils\WordResult;

/**
 */
class GuessRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
      parent::__construct($registry, Guess::class);
  }

  /**
   * Returns one of the worst $n words
   */
  public function findOneOfTheWorsts($limit, $langQuery, $user_id, $langAId, $categoriesStr)
  {   
    /*if($langQuery == 'langA')
      $orderBy = 'w.a2bOk - w.a2bKo';
    if($langQuery == 'langB')
      $orderBy = 'w.b2aOk - w.b2aKo';


    $qb = $this->createQueryBuilder('w')
                ->orderBy($orderBy, 'ASC')
                ->setMaxResults($n)
                ->where('w.user = :user')
                ->setParameter('user', $user_id)
                ->getQuery();

    $result = $qb->execute();

    if(sizeof($result) == 0)
      return [];
      
    $nth_element = rand(1, sizeof($result));

    dump($result[$nth_element - 1]);
    return $result[$nth_element - 1];*/

    if($langQuery == 'langA')
      $orderBy = 'g.a2b_ok - g.a2b_ko';
    if($langQuery == 'langB')
      $orderBy = 'g.b2a_ok - g.b2a_ko';

    $qb = $this->createQueryBuilder('g');
    $em = $this->getEntityManager();

    $categoryCondition = "";
    if(strlen($categoriesStr) > 2)
      $categoryCondition = ' and vc.category_id in '.$categoriesStr.' ';

    // query
    $query = 'SELECT g.id, v.word_a, v.word_b
    FROM guess g, vocabulary v, vocabulary_category vc
    WHERE g.user_id = :user
    and g.vocabulary_id = v.id
    and v.id = vc.vocabulary_id
    and v.language_a = :lang'.$categoryCondition.
    ' ORDER BY '.$orderBy.' ASC LIMIT '.$limit;

    $statement = $em->getConnection()->prepare($query);

    $statement->bindValue('user', $user_id);
    $statement->bindValue('lang', $langAId);
    
    $statement->execute();
    $result = $statement->fetchAll();

    // check size
    if(count($result) == 0)
      return [];

    // choose one random result
    $nth_element = rand(1, sizeof($result));
    $word_result = new WordResult( $result[$nth_element-1] );

    return $word_result;
  }

  /**
   * Returns one of the most unknown $n words
   */
  public function findOneOfTheUnknown($limit, $langQuery, $user_id, $langAId, $categoriesStr)
  {   
    /*if($langQuery == 'langA')
      $orderBy = 'w.a2bOk + w.a2bKo';
    if($langQuery == 'langB')
      $orderBy = 'w.b2aOk + w.b2aKo';

    $qb = $this->createQueryBuilder('w')
                ->orderBy($orderBy, 'ASC')
                ->setMaxResults($n)
                ->where('w.user = :user')
                ->setParameter('user', $user_id)
                ->getQuery();

    $result = $qb->execute();

    $nth_element = rand(1, sizeof($result));

    return $result[$nth_element - 1];*/
    if($langQuery == 'langA')
      $orderBy = 'g.a2b_ok + g.a2b_ko';
    if($langQuery == 'langB')
      $orderBy = 'g.b2a_ok + g.b2a_ko';

    $qb = $this->createQueryBuilder('g');
    $em = $this->getEntityManager();

    $categoryCondition = "";
    if(strlen($categoriesStr) > 2)
      $categoryCondition = ' and vc.category_id in '.$categoriesStr.' ';

    // query
    $query = 'SELECT g.id, v.word_a, v.word_b
    FROM guess g, vocabulary v, vocabulary_category vc
    WHERE g.user_id = :user
    and g.vocabulary_id = v.id
    and v.id = vc.vocabulary_id
    and v.language_a = :lang'.$categoryCondition.
    ' ORDER BY '.$orderBy.' ASC LIMIT '.$limit;

    $statement = $em->getConnection()->prepare($query);

    $statement->bindValue('user', $user_id);
    $statement->bindValue('lang', $langAId);
    
    $statement->execute();
    $result = $statement->fetchAll();

    // check size
    if(count($result) == 0)
      return [];

    // choose one random result
    $nth_element = rand(1, sizeof($result));
    $word_result = new WordResult( $result[$nth_element-1] );

    return $word_result;
  }

  /**
   * Returns one random word
   */
  public function findOneRandom( $user_id, $langAId, $categoriesStr )
  {
    /*$qb = $this->createQueryBuilder('c')
                ->select('count(c.id)')
                ->where('c.user = :user')
                ->setParameter('user', $user_id)
                ->getQuery();
    
    $count = $qb->execute();

    $offset = rand( 0, $count[0][1]-1 );
    if($offset < 0)
      $offset = 0;

    $qb = $this->createQueryBuilder('w')
    ->setMaxResults(1)
    ->where('w.user = :user')
    ->setParameter('user', $user_id)
    ->setFirstResult($offset)
    ->getQuery();

    $result = $qb->execute();

    $nth_element = rand(1, sizeof($result));

    return $result[$nth_element - 1];*/

    $qb = $this->createQueryBuilder('g');
    $em = $this->getEntityManager();

    $categoryCondition = "";
    if(strlen($categoriesStr) > 2)
      $categoryCondition = ' and vc.category_id in '.$categoriesStr.' ';

    // query
    $query = 'SELECT g.id, v.word_a, v.word_b
    FROM guess g, vocabulary v, vocabulary_category vc
    WHERE g.user_id = :user
    and g.vocabulary_id = v.id
    and v.id = vc.vocabulary_id
    and v.language_a = :lang'.$categoryCondition;

    $statement = $em->getConnection()->prepare($query);

    $statement->bindValue('user', $user_id);
    $statement->bindValue('lang', $langAId);
    
    $statement->execute();
    $result = $statement->fetchAll();

    // check size
    if(count($result) == 0)
      return [];

    // choose one random result
    $nth_element = rand(1, sizeof($result));
    $word_result = new WordResult( $result[$nth_element-1] );

    return $word_result;
  }

  /**
   * Returns the number of words a user has.
   */
  public function getCount($user_id)
  {
    $qb = $this->createQueryBuilder('g');

    $em = $this->getEntityManager();
    
    $query = 'SELECT count(guess.id) as count from guess, vocabulary  
    WHERE guess.vocabulary_id = vocabulary.id 
    AND guess.user_id = :user 
    AND vocabulary.language_a = :lang';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', $user_id);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    return $result[0]["count"];
  }

  /**
   * Returns the number of words the user knows how to translate from language A to language B
   */
  public function getKnownA($user_id)
  {
    $em = $this->getEntityManager();
    
    $query = 'SELECT count(guess.id) as count from guess, vocabulary  
    WHERE guess.vocabulary_id = vocabulary.id 
    AND guess.a2b_ok > guess.a2b_ko
    AND guess.user_id = :user  
    AND vocabulary.language_a = :lang';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', $user_id);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    return $result[0]["count"];
  }

  /**
   * Returns the number of words the user knows how to translate from B to A
   */
  public function getKnownB($user_id)
  {
    $em = $this->getEntityManager();
    
    $query = 'SELECT count(guess.id) as count FROM guess, vocabulary  
    WHERE guess.vocabulary_id = vocabulary.id 
    AND guess.b2a_ok > guess.b2a_ko
    AND guess.user_id = :user  
    AND vocabulary.language_a = :lang';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', $user_id);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    if(count($result) == 0)
      return [];

    return $result[0]["count"];
  }

  /**
   * Returns a list of words that the user doesn't know how to translate from language A
   * to language B (his native language)
   */
  public function getWorstA2B($user_id)
  {
    $em = $this->getEntityManager();
    
    $query = 'SELECT v.word_a, v.word_b, g.a2b_ok, g.a2b_ko, g.a2b_ok-g.a2b_ko FROM guess g, vocabulary v 
    WHERE g.a2b_ok-g.a2b_ko < 0 
    AND g.vocabulary_id = v.id 
    AND g.user_id = :user 
    AND v.language_a = :lang
    ORDER by g.a2b_ok-g.a2b_ko';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', $user_id);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    if(count($result) == 0)
      return [];

    return $result;
  }

  /**
   * Returns a list of words that the user doesn't know how to translate from language B (his native language)
   * to language A
   */
  public function getWorstB2A($user_id)
  {
    $em = $this->getEntityManager();
    
    $query = 'SELECT v.word_b, v.word_a, g.b2a_ok, g.b2a_ko, g.b2a_ok-g.b2a_ko FROM guess g, vocabulary v 
    WHERE g.b2a_ok-g.b2a_ko < 0 
    AND g.vocabulary_id = v.id 
    AND g.user_id = :user
    AND v.language_a = :lang
    ORDER by g.b2a_ok-g.b2a_ko';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', $user_id);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    if(count($result) == 0)
      return [];

    return $result;
  }
  public function getQuestionsAnswered($user_id)
  {
    $em = $this->getEntityManager();
    
    $query = 'SELECT sum(guess.a2b_ok)+ sum(guess.a2b_ko) + sum(guess.b2a_ok) + sum(guess.b2a_ko) as count FROM guess, vocabulary
    WHERE guess.vocabulary_id = vocabulary.id
    AND guess.user_id = :user
    AND vocabulary.language_a = :lang';
    
    $statement = $em->getConnection()->prepare($query);

    // FIXME user & lang are hardcoded
    $statement->bindValue('user', $user_id);
    $statement->bindValue('lang', 2);
    $statement->execute();

    $result = $statement->fetchAll();
    return $result[0]["count"];
  }

}
