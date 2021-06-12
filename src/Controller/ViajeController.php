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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_ADMIN")
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

            $inicio =  $form->getData()->getSalida();
            $combi = $form->getData()->getCombi();
            $viajes = $this->getDoctrine()->getRepository(Viaje::class);    
            $viajes = $viajes->findBy(['combi' =>  $combi, 'salida' => $inicio ]);

            if (!$viajes){

                $entityManager = $this->getDoctrine()->getManager();
                $viaje->setEstado('No iniciado');
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
        $capacidad= $viaje->getCombi()->getCapacidad();
        $calidad= $viaje->getCombi()->getCalidad();
        $form = $this->createForm(ViajeType::class, $viaje);
        $form->remove('salida')->remove('llegada')->remove('ruta')->remove('precio');
        $form->handleRequest($request);
        $viajes = $this->getDoctrine()->getRepository(Viaje::class);    
        $viajes = $viajes->findBy(['combi' =>  $form['combi']->getData(), 'salida' => $viaje->getSalida() ]);
        if ($form->isSubmitted() && $form->isValid()) {
            if(!$viaje->inicio()){
                if(!$viajes){
                    if(($form['combi'])->getData()->getCapacidad()>=$capacidad){
                        if(($form['combi'])->getData()->getCalidad()>=$calidad){
                            $this->getDoctrine()->getManager()->flush();
                            return $this->redirectToRoute('viaje_index');
                        }else{
                            $this->addFlash('failed','La calidad de la nueva combi no puede ser menor');
                        }
                    }else{ 
                        $this->addFlash('failed','La capacidad de la nueva combi no puede ser menor');
                    }    
                }else{
                    $this->addFlash('failed','La combi se encuentra en uso');
                }
            }else{
                $this->addFlash('failed','El viaje ya inicio');           
            }  
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

        foreach ($viaje->getTickets() as $ticket){
            $meesage = $ticket->getPasajero()->getUser() .',total a reombolsar: '. $ticket->getPrecioTotal();
            $this->addFlash('rembolso', $meesage);  
            
        }


        if(!$ticket  && !$viaje->inicio()){
            if ($this->isCsrfTokenValid('delete'.$viaje->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($viaje);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('viaje_index');
    }
}
