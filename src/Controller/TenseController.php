<?php

namespace App\Controller;

use App\Entity\Tense;
use App\Form\TenseType;
use App\Repository\TenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tense")
 */
class TenseController extends AbstractController
{
    /**
     * @Route("/", name="tense_index", methods={"GET"})
     */
    public function index(TenseRepository $tenseRepository): Response
    {
        return $this->render('tense/index.html.twig', [
            'tenses' => $tenseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tense_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tense = new Tense();
        $form = $this->createForm(TenseType::class, $tense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tense);
            $entityManager->flush();

            return $this->redirectToRoute('tense_index');
        }

        return $this->render('tense/new.html.twig', [
            'tense' => $tense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tense_show", methods={"GET"})
     */
    public function show(Tense $tense): Response
    {
        return $this->render('tense/show.html.twig', [
            'tense' => $tense,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tense_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tense $tense): Response
    {
        $form = $this->createForm(TenseType::class, $tense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tense_index', [
                'id' => $tense->getId(),
            ]);
        }

        return $this->render('tense/edit.html.twig', [
            'tense' => $tense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tense_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tense $tense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tense_index');
    }
}
