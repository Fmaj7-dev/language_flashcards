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

    $user_id = $this->getUser()->getId();
    $langAId = $session->get('langAId');
    $mode = $session->get('mode');
    
    $linkOk = 'a2bok';
    $linkKo = 'a2bko';

    if($mode == "worst")
      $guess = $repository->findOneOfTheWorsts($user_id, $langAId);
    else if($mode == "random")
      $guess = $repository->findOneRandom($user_id, $langAId);
    else if($mode == "unknown")
      $guess = $repository->findOneOfTheUnknown($user_id, $langAId);
    else
      return ("mode not set");

    $value = $guess->getValue();
    $value = str_replace(",", "<br>", $value);

    return $this->render('verb.html.twig', 
                        ['tenseName' => $guess->getTenseName(),
                         'infinitive' => $guess->getInfinitive(),
                         'value' => $value,
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
    $entityManager = $this->getDoctrine()->getManager();
    $tense_guess = $entityManager->getRepository(TenseGuess::class)->find($id);

    if (!$tense_guess) {
      throw $this->createNotFoundException('No TenseGuess found for id '.$id);
    }   

    $tense_guess->incA2bOk();
    $entityManager->flush();

    return $this->redirectToRoute('app_verb_random', []);
  }

  /** 
  * @Route("/verb/a2bko/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function a2bko($id)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $tense_guess = $entityManager->getRepository(TenseGuess::class)->find($id);

    if (!$tense_guess) {
      throw $this->createNotFoundException('No TenseGuess found for id '.$id);
    }   

    $tense_guess->incA2bKo();
    $entityManager->flush();

    return $this->redirectToRoute('app_verb_random', []);
  }

  /** 
  * @Route("/verb/b2aok/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function b2aok($id)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $tense_guess = $entityManager->getRepository(TenseGuess::class)->find($id);

    if (!$tense_guess) {
      throw $this->createNotFoundException('No TenseGuess found for id '.$id);
    }   

    $tense_guess->incB2aOk();
    $entityManager->flush();

    return $this->redirectToRoute('app_verb_random', []);
  }

  /** 
  * @Route("/verb/b2ako/{id}")
  * @IsGranted("ROLE_USER")
  */
  public function b2ako($id)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $tense_guess = $entityManager->getRepository(TenseGuess::class)->find($id);

    if (!$tense_guess) {
      throw $this->createNotFoundException('No TenseGuess found for id '.$id);
    }   

    $tense_guess->incB2aKo();
    $entityManager->flush();

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
