<?php

namespace App\Controller;

use App\Entity\Vocabulary;
use App\Form\VocabularyType;
use App\Repository\VocabularyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vocabulary")
 */
class VocabularyController extends AbstractController
{
    /**
     * @Route("/", name="vocabulary_index", methods={"GET"})
     */
    public function index(VocabularyRepository $vocabularyRepository): Response
    {
        return $this->render('vocabulary/index.html.twig', [
            'vocabularies' => $vocabularyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="vocabulary_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vocabulary = new Vocabulary();
        $form = $this->createForm(VocabularyType::class, $vocabulary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vocabulary);
            $entityManager->flush();

            return $this->redirectToRoute('vocabulary_index');
        }

        return $this->render('vocabulary/new.html.twig', [
            'vocabulary' => $vocabulary,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vocabulary_show", methods={"GET"})
     */
    public function show(Vocabulary $vocabulary): Response
    {
        return $this->render('vocabulary/show.html.twig', [
            'vocabulary' => $vocabulary,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vocabulary_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vocabulary $vocabulary): Response
    {
        $form = $this->createForm(VocabularyType::class, $vocabulary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vocabulary_index', [
                'id' => $vocabulary->getId(),
            ]);
        }

        return $this->render('vocabulary/edit.html.twig', [
            'vocabulary' => $vocabulary,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vocabulary_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vocabulary $vocabulary): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vocabulary->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vocabulary);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vocabulary_index');
    }
}
