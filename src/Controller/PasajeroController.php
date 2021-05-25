<?php

namespace App\Controller;

use App\Entity\Pasajero;
use App\Entity\User;
use App\Form\PasajeroType;
use App\Form\UserType;
use App\Repository\PasajeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pasajero")
 */
class PasajeroController extends AbstractController
{
    /**
     * @Route("/", name="pasajero_index", methods={"GET"})
     */
    public function index(PasajeroRepository $pasajeroRepository): Response
    {
        return $this->render('pasajero/index.html.twig', [
            'pasajeros' => $pasajeroRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pasajero_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pasajero = new Pasajero();

        $form = $this->createForm(PasajeroType::class, $pasajero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $pasajero->getUser()->setRoles(["ROL_PASAJERO"]);
            $pasajero->setMembresia(false);
            $entityManager->persist($pasajero); 
            $entityManager->flush();

            return $this->redirectToRoute('pasajero_index');
        }

        return $this->render('pasajero/new.html.twig', [
            'pasajero' => $pasajero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pasajero_show", methods={"GET"})
     */
    public function show(Pasajero $pasajero): Response
    {
        return $this->render('pasajero/show.html.twig', [
            'pasajero' => $pasajero,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pasajero_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pasajero $pasajero): Response
    {
        $form = $this->createForm(PasajeroType::class, $pasajero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pasajero_index');
        }

        return $this->render('pasajero/edit.html.twig', [
            'pasajero' => $pasajero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pasajero_delete", methods={"POST"})
     */
    public function delete(Request $request, Pasajero $pasajero): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pasajero->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pasajero);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pasajero_index');
    }
}