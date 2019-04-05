<?php

namespace App\Controller;

use App\Entity\TenseName;
use App\Form\TenseNameType;
use App\Repository\TenseNameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tense/name")
 * @IsGranted("ROLE_ADMIN")
 */
class TenseNameController extends AbstractController
{
    /**
     * @Route("/", name="tense_name_index", methods={"GET"})
     */
    public function index(TenseNameRepository $tenseNameRepository): Response
    {
        return $this->render('tense_name/index.html.twig', [
            'tense_names' => $tenseNameRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tense_name_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tenseName = new TenseName();
        $form = $this->createForm(TenseNameType::class, $tenseName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tenseName);
            $entityManager->flush();

            return $this->redirectToRoute('tense_name_index');
        }

        return $this->render('tense_name/new.html.twig', [
            'tense_name' => $tenseName,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tense_name_show", methods={"GET"})
     */
    public function show(TenseName $tenseName): Response
    {
        return $this->render('tense_name/show.html.twig', [
            'tense_name' => $tenseName,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tense_name_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TenseName $tenseName): Response
    {
        $form = $this->createForm(TenseNameType::class, $tenseName);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tense_name_index', [
                'id' => $tenseName->getId(),
            ]);
        }

        return $this->render('tense_name/edit.html.twig', [
            'tense_name' => $tenseName,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tense_name_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TenseName $tenseName): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tenseName->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tenseName);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tense_name_index');
    }
}
