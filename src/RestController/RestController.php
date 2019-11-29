<?php

namespace App\RestController;

use App\Utils\WordResult;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestController extends FOSRestController
{
  /**
   * Retrieves an Article resource
   * @Rest\Get("/articles/{articleId}")
   */
  public function getArticle(int $articleId): View
  {
    $word = new WordResult;
      // In case our GET was a success we need to return a 200 HTTP OK response with the request object
     //return View::create($word, Response::HTTP_OK);
     $view = View::create();
     return $view;
  }
}
