<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/language")
 */
class LanguageController extends AbstractController
{
    /**
     * @Route("/", name="language_index", methods={"GET"})
     */
    public function index(): Response
    {
        $languages = $this->getDoctrine()
            ->getRepository(Language::class)
            ->findAll();

        return $this->render('language/index.html.twig', [
            'languages' => $languages,
        ]);
    }

    /**
     * @Route("/new", name="language_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $language = new Language();
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($language);
            $entityManager->flush();

            return $this->redirectToRoute('language_index');
        }

        return $this->render('language/new.html.twig', [
            'language' => $language,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="language_show", methods={"GET"})
     */
    public function show(Language $language): Response
    {
        return $this->render('language/show.html.twig', [
            'language' => $language,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="language_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Language $language): Response
    {
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('language_index', [
                'id' => $language->getId(),
            ]);
        }

        return $this->render('language/edit.html.twig', [
            'language' => $language,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="language_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Language $language): Response
    {
        if ($this->isCsrfTokenValid('delete'.$language->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($language);
            $entityManager->flush();
        }

        return $this->redirectToRoute('language_index');
    }
}
