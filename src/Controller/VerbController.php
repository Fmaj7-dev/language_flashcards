<?php
// src/Controller/VerbController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Entity\Verb;
use App\Entity\TenseGuess;

use App\Utils\Table;
use App\Utils\WordResult;

/* 
* 
*/
class VerbController extends AbstractController
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
  }

  /**
  * @Route("/verb/random")
  */
  public function random(SessionInterface $session)
  {
    $repository = $this->getDoctrine()->getRepository( TenseGuess::class );

    $user_id = $this->getUser()->getId();

    $this->setDefaultSession($session);

    $mode = $session->get('mode');
    $langSelected = $session->get('langSelected');   
    $langAName = $session->get('langAName');
    $langBName = $session->get('langBName');
    $langAId = $session->get('langAId');
    $categories = $session->get('categories');

    /*
     
    // Actual language for the query (if user selects 'both' we have to choose one)
    $langQuery = $langSelected;
    if($langSelected == 'both')
      if(rand(0,1)) $langQuery = 'langA';
    else
      $langQuery = 'langB';
      
    $limit = 20;

    if($mode == "worst")
      $guess = $repository->findOneOfTheWorsts(, $langQuery, $user_id, $langAId, $categoriesStr);
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
    }*/

    $guess = $repository->findOneOfTheWorsts();
    $linkOk = 'a2bok';
    $linkKo = 'a2bko';
    $mode = 'worst';

    return $this->render('verb.html.twig', 
                        ['tenseName' => $guess->getTenseName(),
                         'infinitive' => $guess->getInfinitive(),
                         'value' => $guess->getValue(),
                         'id' => $guess->getTenseiGuessId(),
                         'mode' => $mode,
                         'linkOk' => $linkOk,
                         'linkKo' => $linkKo]);
  }

  /** 
  * @Route("/verb/a2bok/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function a2bok($id)
  {
    /*$entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Guess::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }   

    $word->incA2bOk();
    $entityManager->flush();*/

    return $this->redirectToRoute('app_verb_random', []);
  }

  /** 
  * @Route("/verb/a2bko/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function a2bko($id)
  {
    /*$entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Guess::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }   

    $word->incA2bKo();
    $entityManager->flush();*/

    return $this->redirectToRoute('app_verb_random', []);
  }

  /** 
  * @Route("/verb/b2aok/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function b2aok($id)
  {
    /*$entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Guess::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }   

    $word->incB2aOk();
    $entityManager->flush();*/

    return $this->redirectToRoute('app_verb_random', []);
  }

  /** 
  * @Route("/verb/b2ako/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function b2ako($id)
  {
    /*$entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Guess::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }   

    $word->incB2aKo();
    $entityManager->flush();*/

    return $this->redirectToRoute('app_verb_random', []);
  }

  /**
  * @Route("/verb/setSort/{mode}")
  * @IsGranted("ROLE_USER")
  */
  public function setSort($mode, SessionInterface $session)
  {
    $session->set('mode', $mode);
    return $this->redirectToRoute('app_verb_random', []);
  }

 /**
  * @Route("/verb/setLang/{lang}")
  * @IsGranted("ROLE_USER")
  */
  public function setLang($lang)
  {
    $session = $this->get('session');
    $session->set('langSelected', $lang);
    return $this->redirectToRoute('app_verb_random', []);
  }
 }
