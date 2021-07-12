<?php

namespace App\Controller;

use App\Entity\Viaje;
use App\Entity\Ticket;
use App\Entity\Combi;
use App\Entity\Tercero;
use App\Form\ViajeType;
use App\Repository\ViajeRepository;
use App\Repository\ImprevistoRepository;
use App\Repository\TerceroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * 
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
    public function show(ImprevistoRepository $imprevistoRepository, Viaje $viaje): Response
    {   
        $repository= $this->getDoctrine()->getRepository(Tercero::class);  
        $terceros = $repository->findTercerosBy($viaje);

        return $this->render('viaje/show.html.twig', [
            'viaje' => $viaje,
            'pasajeros' => $viaje->getPasajeros(),
            'imprevistos' => $imprevistoRepository->findBy(['viaje'=> $viaje->getId()]),
            'terceros' => $terceros,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="viaje_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Viaje $viaje): Response
    {
        $asientosOcupados= $viaje->asientosOcupados();
        $calidad= $viaje->getCombi()->getCalidad();
        $form = $this->createForm(ViajeType::class, $viaje);
        $form->remove('salida')->remove('llegada')->remove('ruta')->remove('precio');
        $form->handleRequest($request);
        $viajes = $this->getDoctrine()->getRepository(Viaje::class);    
        $viajes = $viajes->findBy(['combi' =>  $form['combi']->getData(), 'salida' => $viaje->getSalida() ]);

        //Chequeo de calidad
        $cambio= $calidad != ($form['combi'])->getData()->getCalidad(); //Guardamos si cambio la calidad
        $vacia = ($viaje->asientosOcupados()==0); //guardamos si esta vacia

        if ($form->isSubmitted() && $form->isValid()) {
            if($viaje->getEstado() == "No iniciado"){
                if(!$viajes){
                    if(($form['combi'])->getData()->getCapacidad()>= $asientosOcupados){

                        if($vacia || !($vacia || $cambio)){ //Chequeo de calidad

                            $this->getDoctrine()->getManager()->flush();
                            return $this->redirectToRoute('viaje_index');

                        }else{
                            $this->addFlash('failed','La calidad de la nueva combi no puede ser menor si ya se vendieron pasajes');
                        }
                    }else{ 
                        $this->addFlash('failed','Esta combi no tiene suficientes asientos para la cantidad de pasajes ya vendidos');
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
        if( $viaje->getEstado() == "No iniciado"){
            
            foreach ($viaje->getTickets() as $ticket){
                $meesage = $ticket->getPasajero()->getUser() .',total a rembolsar: '. $ticket->getPrecioTotal();
                $this->addFlash('rembolso', $meesage);  
                
            }
            
            if ($this->isCsrfTokenValid('delete'.$viaje->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($viaje);
                $entityManager->flush();
            }
        }else{
        $this->addFlash('failed','No se puede eliminar un viaje ya iniciado');
        }
        return $this->redirectToRoute('viaje_index');
    }

    /**
     * @Route("/{id}/iniciar", name="viaje_iniciar", methods={"POST"} )
     */
    public function iniciar(Viaje $viaje): Response
    {
        date_default_timezone_set('America/Buenos_Aires');
        $now = new \DateTime();
        $chofer = $viaje->getCombi()->getChofer();
        
        if($viaje->getSalida()<$now){ 
            if($viaje->getEstado()!="Finalizado"){
                if(!$viaje->getCombi()->getChofer()->getViajando()){

                    $entityManager = $this->getDoctrine()->getManager();
                    $viaje->iniciar();
                    $chofer->iniciarViaje();
                    $entityManager->persist($viaje);
                    $entityManager->persist($chofer);
                    $entityManager->flush();
                    $this->addFlash('success', 'Se inicio el viaje correctamente');

                }else {
                    $this->addFlash('failed', 'Ya tienes un viaje iniciado');
                }
            }else{
                $this->addFlash('failed', 'El viaje ya se encuentra finalizado');
            }
        }else{
            $this->addFlash('failed', 'Todavia no se puede iniciar');
        }

        return $this->redirectToRoute('viaje_show',['id' => $viaje->getId() ]);
    }

    /**
     * @Route("/{id}/finalizar", name="viaje_finalizar", methods={"POST"} )
     */
    public function finalizar(Viaje $viaje): Response
    {
        date_default_timezone_set('America/Buenos_Aires');
        $now = new \DateTime();
        $chofer = $viaje->getCombi()->getChofer();
        if($viaje->getEstado()=="En curso"){

            $entityManager = $this->getDoctrine()->getManager();
            $viaje->finalizar($now);
            $chofer->finalizarViaje();
            $entityManager->persist($chofer);
            $entityManager->persist($viaje);
            $entityManager->flush();
            $this->addFlash('success', 'Se finalizo correctamente');

        }else{
            $this->addFlash('failed', 'El viaje no se encuentra en curso');
        }
        

        return $this->redirectToRoute('viaje_show',['id' => $viaje->getId() ]);
    }

    
}
