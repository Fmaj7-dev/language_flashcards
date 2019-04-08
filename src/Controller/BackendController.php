<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Utils\Table;
use App\Entity\Guess;

/**
 * @Route("/backend")
 * @IsGranted("ROLE_ADMIN")
 */
class BackendController extends AbstractController
{
    /**
     * @Route("/", name="backend_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('backend/index.html.twig', [
        ]);
    }
    /**
     * @Route("/category_editor", name="category_editor", methods={"GET"})
     */
    public function categoryEditor(): Response
    {
        $repository = $this->getDoctrine()->getRepository( Guess::class );

        $stats = new Table("French to Spanish", array("French", "Spanish", "ok", "ko", "diff"));
        $rows = $repository->getWorstA2B(1);
        $stats->setRows($rows);

        $tables [] = $stats;

        return $this->render('backend/category_editor.html.twig', 
                                ['Tables' => $tables]
                            );
    }
}
