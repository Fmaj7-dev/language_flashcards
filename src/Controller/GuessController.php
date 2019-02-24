<?php
// src/Controller/GuessController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Guess;

class GuessController extends AbstractController
{
  /**
  * @Route("/guess")
  */
  public function random(SessionInterface $session)
  {
    $repository = $this->getDoctrine()->getRepository(Guess::class);
    $word = $repository->findOneOfTheWorsts(20);
  }
 }
