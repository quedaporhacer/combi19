<?php

namespace App\Controller;

use App\Entity\Chofer;
use App\Entity\Combi;
use App\Form\ChoferType;
use App\Repository\ChoferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chofer")
 */
class ChoferController extends AbstractController
{
    /**
     * @Route("/", name="chofer_index", methods={"GET"})
     */
    public function index(ChoferRepository $choferRepository): Response
    {
        return $this->render('chofer/index.html.twig', [
            'chofers' => $choferRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="chofer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $chofer = new Chofer();
        $form = $this->createForm(ChoferType::class, $chofer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email= $form->getData()->getEmail();
            $repository=$this->getDoctrine()->getRepository(Chofer::class);
            $otherChofer= $repository->findOneBy(['email' =>  $email ]);

            if(!$otherChofer){

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($chofer);
                $entityManager->flush();
                return $this->redirectToRoute('chofer_index');
            }
            $this->addFlash('failed', 'El email ya se encuentra registrado!');
           
        }

        return $this->render('chofer/new.html.twig', [
            'chofer' => $chofer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chofer_show", methods={"GET"})
     */
    public function show(Chofer $chofer): Response
    {
        return $this->render('chofer/show.html.twig', [
            'chofer' => $chofer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="chofer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Chofer $chofer): Response
    {
        $form = $this->createForm(ChoferType::class, $chofer);
        $form->remove('nombre')->remove('apellido');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('chofer_index');
        }

        return $this->render('chofer/edit.html.twig', [
            'chofer' => $chofer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chofer_delete", methods={"POST"})
     */
    public function delete(Request $request, Chofer $chofer): Response
    {   
        $repository = $this->getDoctrine()->getRepository(Combi::class);
        $combi= $repository->findOneBy(['chofer' =>  $chofer ]);

        if(!$combi){

          if ($this->isCsrfTokenValid('delete'.$chofer->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($chofer);
                $entityManager->flush();
            }  

        }

        

        return $this->redirectToRoute('chofer_index');
    }
}