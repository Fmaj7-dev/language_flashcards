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

    if(!$session->has('langAName'))
      $session->set('langAName', 'french');

    if(!$session->has('langBName'))
      $session->set('langBName', 'spanish');   

    if(!$session->has('langSelected'))
      $session->set('langSelected', 'both');

    if(!$session->has('langAId'))
      $session->set('langAId', 2);   
    
    if(!$session->has('langBId'))
      $session->set('langBId', 1);   
  }

  /**
   * @Route("/")
   */
public function welcome(SessionInterface $session)
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

    
    // Actual language for the query (if user selects 'both' we have to choose one)
    $langQuery = $langSelected;
    if($langSelected == 'both')
      if(rand(0,1)) $langQuery = 'langA';
    else
      $langQuery = 'langB';
      
    if($mode == "worst")
    {
      $guess = $repository->findOneOfTheWorsts(1, $langQuery, $user_id);
    }
    else if($mode == "random")
    {
      $guess = $repository->findOneRandom($user_id);
    }
    else if($mode == "unknown")
    {
      $guess = $repository->findOneOfTheUnknown(1, $langQuery, $user_id);
    }
    else
    {
      return ("mode not set");
    }
    $vocabulary = $guess->getVocabulary();

    
    // variables to pass to the template
    if($langQuery == 'langA')
    {
      // Name of the language of the question
      $langQueryName = $langAName;
      // Name of the language of the answer
      $langNotQueryName = $langBName;
      // Word asked
      $wordQuestioned = $vocabulary->getWordA($user_id);
      // response
      $wordAnswered = $vocabulary->getWordB($user_id);
      // link ok
      $linkOk = 'a2bok';
      // link ko
      $linkKo = 'a2bko';
    }
    else
    {
      // Name of the language of the question
      $langQueryName = $langBName;
      // Name of the language of the answer
      $langNotQueryName = $langAName;
      // Word asked
      $wordQuestioned = $vocabulary->getWordB($user_id);
      // response
      $wordAnswered = $vocabulary->getWordA($user_id);
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
                         'langBName' => $langBName]);
    
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

    $user_id = $this->getUser()->getId();

    $word->incA2bOk($user_id);
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

    $user_id = $this->getUser()->getId();

    $word->incA2bKo($user_id);
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

    $user_id = $this->getUser()->getId();

    $word->incB2aOk($user_id);
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

    $user_id = $this->getUser()->getId();

    $word->incB2aKo($user_id);
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
