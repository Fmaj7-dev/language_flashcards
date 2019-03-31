<?php
// src/Controller/VocabularyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Entity\Vocabulary;
use App\Entity\Guess;

use App\Utils\Table;
use App\Utils\WordResult;

/* 
* 
*/
class VocabularyController extends AbstractController
{
  public function setDefaultSession(SessionInterface $session)
  {
    // global variables
    if(!$session->has('mode'))
      $session->set('mode', 'random');

    // name of the language the user wants to learn in this session
    if(!$session->has('langAName'))
      $session->set('langAName', 'french');

    // name of the native language of the user
    if(!$session->has('langBName'))
      $session->set('langBName', 'spanish');   
    
    // id of the language the user wants to learn in this session
    if(!$session->has('langAId'))
      $session->set('langAId', 2);   
    
    // id of the native language of the user
    if(!$session->has('langBId'))
      $session->set('langBId', 1);   

    // language the user wants the app to ask him about
    if(!$session->has('langSelected'))
      $session->set('langSelected', 'both');

    if(!$session->has('categories'))
    {
      $categories = [
        'noun' => true,
        'verb' => true,
        'expression' => false
      ];
      $session->set('categories', $categories);
    } 
  }

  public function getCategoriesAsStr($categories)
  {
    $string = '(';

    if( array_key_exists ("noun", $categories) && $categories["noun"]) 
      $string = $string.'1,';
    if( array_key_exists ("verb", $categories) && $categories["verb"]) 
      $string = $string.'2,';
    if( array_key_exists ("adjective", $categories) && $categories["adjective"]) 
      $string = $string.'3,';
    if( array_key_exists ("adverb", $categories) && $categories["adverb"]) 
      $string = $string.'4,';
    if( array_key_exists ("pronoun", $categories) && $categories["pronoun"]) 
      $string = $string.'5,';
    if( array_key_exists ("preposition", $categories) && $categories["preposition"]) 
      $string = $string.'6,';
    if( array_key_exists ("conjunction", $categories) && $categories["conjunction"]) 
      $string = $string.'7,';
    if( array_key_exists ("determiner", $categories) && $categories["determiner"]) 
      $string = $string.'8,';
    if( array_key_exists ("exclamation", $categories) && $categories["exclamation"]) 
      $string = $string.'9,';
    if( array_key_exists ("expression", $categories) && $categories["expression"]) 
      $string = $string.'10,';

    $string = rtrim($string,',');
    $string = $string.')';

    return $string;
  }

  /**
  * @Route("/")
  */
  public function welcome()
  {
    return $this->render('welcome.html.twig', []);
  }

  /**
  * @Route("/random")
  * @IsGranted("ROLE_USER")
  */
  public function random(SessionInterface $session)
  {
    $repository = $this->getDoctrine()->getRepository( Guess::class );

    $user_id = $this->getUser()->getId();

    $this->setDefaultSession($session);

    $mode = $session->get('mode');
    $langSelected = $session->get('langSelected');   
    $langAName = $session->get('langAName');
    $langBName = $session->get('langBName');
    $langAId = $session->get('langAId');
    $categories = $session->get('categories');

    $categoriesStr = $this->getCategoriesAsStr($categories);
     
    // Actual language for the query (if user selects 'both' we have to choose one)
    $langQuery = $langSelected;
    if($langSelected == 'both')
      if(rand(0,1)) $langQuery = 'langA';
    else
      $langQuery = 'langB';
      
    $limit = 20;

    if($mode == "worst")
      $guess = $repository->findOneOfTheWorsts($langQuery, $user_id, $langAId, $categoriesStr);
    else if($mode == "random")
      $guess = $repository->findOneRandom($user_id, $langAId, $categoriesStr);
    else if($mode == "unknown")
      $guess = $repository->findOneOfTheUnknown($limit, $langQuery, $user_id, $langAId, $categoriesStr);
    else
      return ("mode not set");

    if($langQuery == 'langA')
    {
      // Name of the language of the question
      $langQueryName = $langAName;
      // Name of the language of the answer
      $langNotQueryName = $langBName;
    }
    else
    {
      // Name of the language of the question
      $langQueryName = $langBName;
      // Name of the language of the answer
      $langNotQueryName = $langAName;
    }

    if (!is_object($guess))
    {
      return $this->render( 'vocabulary.html.twig', [
      'mode' => $mode,
      'langQueryName' => $langQueryName,
      'langNotQueryName' => $langNotQueryName,
      'langSelected' => $langSelected,
      'langAName' => $langAName,
      'langBName' => $langBName,
      'categories' => $session->get('categories')] );
    }

    // variables to pass to the template
    if($langQuery == 'langA')
    {
      // Word asked
      $wordQuestioned = $guess->getWordA();
      // response
      $wordAnswered = $guess->getWordB();
      // link ok
      $linkOk = 'a2bok';
      // link ko
      $linkKo = 'a2bko';
    }
    else
    {
      // Word asked
      $wordQuestioned = $guess->getWordB();
      // response
      $wordAnswered = $guess->getWordA();
      // link ok
      $linkOk = 'b2aok';
      // link ko
      $linkKo = 'b2ako';
    }

    return $this->render('vocabulary.html.twig', 
                        ['wordQuestioned' => $wordQuestioned,
                         'wordAnswered' => $wordAnswered,
                         'id' => $guess->getId(),
                         'linkOk' => $linkOk,
                         'linkKo' => $linkKo,
                         'mode' => $mode,
                         'langQueryName' => $langQueryName,
                         'langNotQueryName' => $langNotQueryName,
                         'langSelected' => $langSelected,
                         'langAName' => $langAName,
                         'langBName' => $langBName,
                         'categories' => $session->get('categories')]);
  }

  /** 
  * @Route("/a2bok/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function a2bok($id)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Guess::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }   

    $word->incA2bOk();
    $entityManager->flush();

    return $this->redirectToRoute('app_vocabulary_random', []);
  }

  /** 
  * @Route("/a2bko/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function a2bko($id)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Guess::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }   

    $word->incA2bKo();
    $entityManager->flush();

    return $this->redirectToRoute('app_vocabulary_random', []);
  }

  /** 
  * @Route("/b2aok/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function b2aok($id)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Guess::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }   

    $word->incB2aOk();
    $entityManager->flush();

    return $this->redirectToRoute('app_vocabulary_random', []);
  }

  /** 
  * @Route("/b2ako/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function b2ako($id)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Guess::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }   

    $word->incB2aKo();
    $entityManager->flush();

    return $this->redirectToRoute('app_vocabulary_random', []);
  }

  /**
  * @Route("/setSort/{mode}")
  * @IsGranted("ROLE_USER")
  */
  public function setSort($mode, SessionInterface $session)
  {
    $session->set('mode', $mode);
    return $this->redirectToRoute('app_vocabulary_random', []);
  }

 /**
  * @Route("/setLang/{lang}")
  * @IsGranted("ROLE_USER")
  */
  public function setLang($lang)
  {
    $session = $this->get('session');
    $session->set('langSelected', $lang);
    return $this->redirectToRoute('app_vocabulary_random', []);
  }

  /**
  * @Route("/setCat/{category}")
  * @IsGranted("ROLE_USER")
  */
  public function setCategory(SessionInterface $session, $category)
  {

    $categories = $session->get('categories');
    if($categories[$category] == true)
      $categories[$category] = false;
    else
      $categories[$category] = true;

    $session->set('categories', $categories);

    return $this->redirectToRoute('app_vocabulary_random', []);
  }

  /**
  * @Route("/stats")
  * @IsGranted("ROLE_USER")
  */
  public function stats(SessionInterface $session)
  {
    // table general
    $stats = new Table("General Statistics", array("Name", "Value"));
    $repository = $this->getDoctrine()->getRepository( Guess::class );

    $user_id = $this->getUser()->getId();

    $count = $repository->getCount($user_id);

    $knownA = $repository->getKnownA($user_id);
    $percentageA = $knownA*100/$count;

    $knownB = $repository->getKnownB($user_id);
    $percentageB = $knownB*100/$count;

    $stats->appendRow(["Number of words", $count]);
    $stats->appendRow(["Known French words", $knownA." ".$percentageA."%"]);
    $stats->appendRow(["Known Spanish words", $knownB." ".$percentageB."%"]);
    $stats->appendRow(["Num questions answered", $repository->getQuestionsAnswered($user_id)]);
    
    $stats2 = new Table("French to Spanish", array("French", "Spanish", "ok", "ko", "diff"));
    $rows = $repository->getWorstA2B($user_id);
    $stats2->setRows($rows);

    $stats3 = new Table("Spanish to French", array("Spanish", "French", "ok", "ko", "diff"));
    $rows = $repository->getWorstB2A($user_id);
    $stats3->setRows($rows);

    $tables [] = $stats;
    $tables [] = $stats2;
    $tables [] = $stats3;

    return $this->render('stats.html.twig', 
                        ['Tables' => $tables]);
  }
 }
