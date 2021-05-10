<?php

namespace App\Controller;

use App\Entity\Viaje;
use App\Entity\Ticket;
use App\Entity\Combi;
use App\Form\ViajeType;
use App\Repository\ViajeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/viaje")
 */
class ViajeController extends AbstractController
{
    /**
     * @Route("/", name="viaje_index", methods={"GET"})
     */
    public function index(ViajeRepository $viajeRepository): Response
    {
        return $this->render('viaje/index.html.twig', [
            'viajes' => $viajeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="viaje_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $viaje = new Viaje();
        $form = $this->createForm(ViajeType::class, $viaje);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $combi = $form->getData()->getCombi();
            $viajes = $this->getDoctrine()->getRepository(Viaje::class);    
            $viajes = $viajes->findBy(['combi' =>  $combi ]);

            $disponible=true;
            foreach ($viajes as $viajeeach)
            {   
                if(!$viajeeach->disponible()){
                    $disponible=false;
                }
            }


        
            if(!$viajes || $disponible ){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($viaje);
                $entityManager->flush();
                return $this->redirectToRoute('viaje_index');
            }

            $this->addFlash('failed','la combi no esta disponible');
            
        }

        return $this->render('viaje/new.html.twig', [
            'viaje' => $viaje,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="viaje_show", methods={"GET"})
     */
    public function show(Viaje $viaje): Response
    {
        return $this->render('viaje/show.html.twig', [
            'viaje' => $viaje,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="viaje_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Viaje $viaje): Response
    {
        $form = $this->createForm(ViajeType::class, $viaje);
        $form->remove('salida')->remove('llegada')->remove('ruta');
        $form->handleRequest($request);
        


        if ($form->isSubmitted() && $form->isValid()) {

                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('viaje_index');

        }

        return $this->render('viaje/edit.html.twig', [
            'viaje' => $viaje,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="viaje_delete", methods={"POST"})
     */
    public function delete(Request $request, Viaje $viaje): Response
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $ticket= $repository->findOneBy(['viaje' =>  $viaje ]);
        
        if(!$ticket){
            if ($this->isCsrfTokenValid('delete'.$viaje->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($viaje);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('viaje_index');
    }
}