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
  /**
  * @Route("/random")
  * @Route("/")
  */
  public function random(SessionInterface $session)
  {
    $repository = $this->getDoctrine()->getRepository(Guess::class);
    //$word = $repository->findOneOfTheWorsts(20);
    return new Response("test");

    /*$repository = $this->getDoctrine()->getRepository(Vocabulary::class);
    $word = $repository->findOneOfTheWorsts(20);

    $mode = $session->get('mode');

    //return new Response('Check out this great word: '.$word->getFrench());
    return $this->render('vocabulary.html.twig', 
                        ['word' => $word->getFrench(),
                         'id' => $word->getId(),
                         'linkOk' => 'f2sok',
                         'linkKo' => 'f2sko',
                         'mode' => $mode]);*/
  }

  /**
  * @Route("/f2sok/{id}")
  */
  public function f2sok($id)
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
  public function s2fok($id)
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
 }
