<?php
// src/Controller/VocabularyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Vocabulary;
use App\Entity\Guess;

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

    $langQuery = 'langB';
    if($langSelected == 'both')
      if(rand(0,1)) $langQuery = 'langA';

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
  
    return $this->render('vocabulary.html.twig', 
                        ['wordA' => $vocabulary->getWordA(),
                         'wordB' => $vocabulary->getWordB(),
                         'id' => $guess->getId(),
                         'linkOk' => 'a2bok',
                         'linkKo' => 'b2ako',
                         'mode' => $mode,
                         'langSelected' => $langSelected,
                         'langAName' => $langAName,
                         'langBName' => $langBName]);
    
  }

  /** 
  * @Route("/a2bok/{id}")
  */
  public function a2bok($id)
  {
    /*$entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Vocabulary::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }   

    $word->incF2sOk();
    $entityManager->flush();

    return $this->redirectToRoute('app_vocabulary_random', []);*/
  }



  /**
  * @Route("/f2sko/{id}")
  */
  public function b2aok($id)
  {
    /*$entityManager = $this->getDoctrine()->getManager();
    $word = $entityManager->getRepository(Vocabulary::class)->find($id);

    if (!$word) {
      throw $this->createNotFoundException('No word found for id '.$id);
    }

    $word->incS2fOk();
    $entityManager->flush();

    return $this->redirectToRoute('app_vocabulary_random', []);*/
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
 }
