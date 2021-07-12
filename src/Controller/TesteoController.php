<?php

namespace App\Controller;

use App\Entity\Pasajero;
use App\Entity\Tercero;
use App\Entity\Ticket;
use App\Form\TesteoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
* @Route("/testeo")
*/
class TesteoController extends AbstractController
{
    /**
     * @Route("/", name="testeo")
     */
    public function index(): Response
    {   
        
        return $this->render('testeo/index.html.twig', [
            'controller_name' => 'TesteoController',
        ]);
    }


     /**
     * @Route("/{ticket}/{pasajero}/new", name="testeo_new", methods={"GET","POST"})
     */
    public function new(Ticket $ticket,Pasajero $pasajero, Request $request): Response
    {
        $form = $this->createForm(TesteoType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
           

            $temperatura = $form->getData()['temperatura'];


            $sintomas=0;
            $i=0;
            foreach($form->getData() as $val){
                if($i < 4){
                    $i++;
                    if ($val) {
                        $sintomas++;
                    }    
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            if(!($temperatura>='38' ||  $sintomas >= 2)){

                $ticket->setTesteo(true);
                $this->addFlash('success','Paso el testeo corretamente');
            
            }else {
                $ticket->setTesteo(false);
                $pasajero->setRestriccion();
                $ticket->setReembolso(false); // ha reembolsar
                $ticket->setDescripcionReembolso('El pasajero registrado '.$pasajero->getUser().' con dni '.$pasajero->getDni().' se le debe reembolsar porque es sospechoso de covid'); // ha reembolsar
                $this->addFlash('failed','No paso el testeo, usuario inhabilitado para el viaje');
            }
            $entityManager->persist($ticket);
            $entityManager->flush();
            return $this->redirectToRoute('viaje_show',['id' => $ticket->getViaje()->getId() ]);      

        }
        
        return $this->render('testeo/index.html.twig', [
            'form' => $form->createView(),
            'pasajero' => $pasajero, 
            'controller_name' => 'TesteoController'
        ]);
    }

     /**
     * @Route("/tercero/{ticket}/{tercero}/new", name="testeoTercero_new", methods={"GET","POST"})
     */
    public function newTercero(Ticket $ticket,Tercero $tercero, Request $request): Response
    {
        $form = $this->createForm(TesteoType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
           

            $temperatura = $form->getData()['temperatura'];


            $sintomas=0;
            $i=0;
            foreach($form->getData() as $val){
                if($i < 5){
                    $i++;
                    if ($val) {
                        $sintomas++;
                    }    
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            if(!($temperatura>='38' ||  $sintomas >= 2)){

                $tercero->setTesteo(true);
                $this->addFlash('success','Paso el testeo corretamente');
            
            }else {
                $tercero->setTesteo(false);
                $tercero->setReembolso(false); // ha reembolsar
                $tercero->setDescripcionReembolso('Se debe reembolsar ya que el pasajero invitado/tercero '.$tercero->getNombre().' '.$tercero->getNombre().' con dni '.$tercero->getDni().' fue sospechado de covid'); // ha reembolsar
                $this->addFlash('failed','No paso el testeo, usuario inhabilitado para el viaje');
            }
            $entityManager->persist($ticket);
            $entityManager->flush();
            return $this->redirectToRoute('viaje_show',['id' => $ticket->getViaje()->getId() ]);      

        }
        
        return $this->render('testeo/index.html.twig', [
            'form' => $form->createView(),
            'pasajero' => $tercero, 
            'controller_name' => 'TesteoController'
        ]);
    }

    
}
