<?php

namespace App\Controller;

use App\Entity\Insumo;
use App\Form\InsumoType;
use App\Repository\InsumoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/insumo")
 */
class InsumoController extends AbstractController
{
    /**
     * @Route("/", name="insumo_index", methods={"GET"})
     */
    public function index(InsumoRepository $insumoRepository): Response
    {
        return $this->render('insumo/index.html.twig', [
            'insumos' => $insumoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="insumo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $insumo = new Insumo();
        $form = $this->createForm(InsumoType::class, $insumo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($insumo);
            $entityManager->flush();

            return $this->redirectToRoute('insumo_index');
        }

        return $this->render('insumo/new.html.twig', [
            'insumo' => $insumo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="insumo_show", methods={"GET"})
     */
    public function show(Insumo $insumo): Response
    {
        return $this->render('insumo/show.html.twig', [
            'insumo' => $insumo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="insumo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Insumo $insumo): Response
    {
        $form = $this->createForm(InsumoType::class, $insumo);
        $form->remove('nombre')->remove('tipo');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('insumo_index');
        }

        return $this->render('insumo/edit.html.twig', [
            'insumo' => $insumo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="insumo_delete", methods={"POST"})
     */
    public function delete(Request $request, Insumo $insumo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$insumo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($insumo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('insumo_index');
    }
}
