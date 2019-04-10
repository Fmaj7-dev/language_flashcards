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
     * @Route("/category_editor/{offset}/{size}", name="category_editor", methods={"GET"})
     */
    public function categoryEditor($offset = 0, $size = 20): Response
    {
        $repository = $this->getDoctrine()->getRepository( Guess::class );

        $stats = new Table("French to Spanish", array("French", "Spanish", "Noun", "Verb", "Adjective", "Adverb", "Pronoun", "Preposition", "Conjunction", "Determiner", "Exclamation", "Expression"));
        
        $rows = $repository->getCategories($offset, $size);

        foreach($rows as $row)
        {
            $to_insert ["id"] = $row["id"];
            $to_insert ["word_a"] = $row["word_a"];
            $to_insert ["word_b"] = $row["word_b"];
            $expanded = str_getcsv($row["categories"]);

            for ($x = 1; $x <= 10; $x++)
            {
                if (in_array($x, $expanded))
                    $to_insert[$x] = true;
                else
                    $to_insert[$x] = false;
            }

            //dump($to_insert);
            $stats->appendRow( $to_insert );
            $to_insert = array();
            $expanded = array();

        }

        $tables [] = $stats;

        return $this->render('backend/category_editor.html.twig', 
                                ['Tables' => $tables,
                                'next_offset' => strval($offset+$size),
                                'prev_offset' => strval($offset-$size),
                                'size' => $size]
                            );
    }

    /**
     * @Route("/category_editor/add/{word}/{cat}", name="add", methods={"GET"})
     */
    public function add( $word, $cat ): Response
    {
        $repository = $this->getDoctrine()->getRepository( Guess::class );
        $repository->makeSureItExists($word, $cat);

        return new Response(
            '{result: ok}'
        );
    }

    /**
     * @Route("/category_editor/remove/{word}/{cat}", name="remove", methods={"GET"})
     */
    public function remove( $word, $cat ): Response
    {
        $repository = $this->getDoctrine()->getRepository( Guess::class );
        $repository->remove($word, $cat);

        return new Response(
            '{result: ok}'
        );
    }
}
