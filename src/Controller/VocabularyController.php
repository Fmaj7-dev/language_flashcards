<?php
// src/Controller/VocabularyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Vocabulary;
use App\Entity\Guess;

use App\Utils\Table;

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

    if(!$session->has('langStudyingId'))
      $session->set('langStudyingId', 2);   
  }

  /**
  * @Route("/random")
  * @Route("/")
  */
  public function random(SessionInterface $session)
  {
    $repository = $this->getDoctrine()->getRepository( Guess::class );

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
      $guess = $repository->findOneOfTheWorsts(1, $langQuery);
    }
    else if($mode == "random")
    {
      $guess = $repository->findOneRandom();
    }
    else if($mode == "unknown")
    {
      $guess = $repository->findOneOfTheUnknown(1, $langQuery);
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
      $wordQuestioned = $vocabulary->getWordA();
      // response
      $wordAnswered = $vocabulary->getWordB();
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
      $wordQuestioned = $vocabulary->getWordB();
      // response
      $wordAnswered = $vocabulary->getWordA();
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
  */
  public function setSort($mode, SessionInterface $session)
  {
    $session->set('mode', $mode);
    return $this->redirectToRoute('app_vocabulary_random', []);
  }

 /**
  * @Route("/setLang/{lang}")
  */
  public function setLang($lang)
  {
    $session = $this->get('session');
    $session->set('langSelected', $lang);
    return $this->redirectToRoute('app_vocabulary_random', []);
  }

  /**
  * @Route("/stats")
  */
  public function stats(SessionInterface $session)
  {
    // table general
    $stats = new Table(2, array("Name", "Value"));
    $repository = $this->getDoctrine()->getRepository( Guess::class );
    $stats->appendRow(["Number of words", $repository->getCount()]);

    $stats2 = new Table(2, array("asdf", "qwer"));
    $repository = $this->getDoctrine()->getRepository( Guess::class );
    $stats2->appendRow(["Number of words", $repository->getCount()]);

    $tables [] = $stats;
    $tables [] = $stats2;


    return $this->render('stats.html.twig', 
                        ['Tables' => $tables]);
  }
 }
