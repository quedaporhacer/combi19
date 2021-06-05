<?php

namespace App\Controller;

use App\Entity\Tarjeta;
use App\Entity\Pasajero;
use App\Form\Tarjeta1Type;
use App\Repository\TarjetaRepository;
use App\Repository\PasajeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tarjeta")
 */
class TarjetaController extends AbstractController
{
    /**
     * @Route("/", name="tarjeta_index", methods={"GET"})
     */
    public function index(TarjetaRepository $tarjetaRepository): Response
    {
        return $this->render('tarjeta/index.html.twig', [
            'tarjetas' => $tarjetaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="tarjeta_new", methods={"GET","POST"})
     */
    public function new(Pasajero $pasajero, Request $request): Response
    {
        $tarjetum = new Tarjeta();
        $form = $this->createForm(Tarjeta1Type::class, $tarjetum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $tarjetum->setPropietario($pasajero);
            $entityManager->persist($tarjetum);
            $entityManager->flush();

            return $this->redirectToRoute('tarjeta_index');
        }

        return $this->render('tarjeta/new.html.twig', [
            'tarjetum' => $tarjetum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tarjeta_show", methods={"GET"})
     */
    public function show(Tarjeta $tarjetum): Response
    {
        return $this->render('tarjeta/show.html.twig', [
            'tarjetum' => $tarjetum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tarjeta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tarjeta $tarjetum): Response
    {
        $form = $this->createForm(Tarjeta1Type::class, $tarjetum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tarjeta_index');
        }

        return $this->render('tarjeta/edit.html.twig', [
            'tarjetum' => $tarjetum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tarjeta_delete", methods={"POST"})
     */
    public function delete(Request $request, Tarjeta $tarjetum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tarjetum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tarjetum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tarjeta_index');
    }
}
