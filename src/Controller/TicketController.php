<?php

namespace App\Controller;

use App\Entity\Pasajero;
use App\Entity\Ticket;
use App\Entity\Viaje;
use App\Form\TicketType;
use App\Repository\PasajeroRepository;
use App\Repository\TerceroRepository;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
/**
 * @Route("/ticket")
 */
class TicketController extends AbstractController
{


    /**
     * @Route("/", name="ticket_index", methods={"GET"})
     */
    public function index(TicketRepository $ticketRepository): Response
    {
        $ticketACobrar = $ticketRepository->findBy(['cobro'=>false]);
        $ticketCobrados = $ticketRepository->findBy(['cobro'=>true]);

        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticketACobrar,
            'ticketsCobrados' => $ticketCobrados,
        ]);
    }

    /**
     * @Route("/gold/{id}/new", name="tarjeta_compra_new", methods={"GET","POST"})
     */
    public function tarjeta(Request $request, Viaje $viaje,PasajeroRepository $pasajeroRepository): Response
    {
        $pasajero = $pasajeroRepository->findOneBy(['user'=>$this->getUser()->getId()]);
       
        if($pasajero->getTarjeta() == null){
            return $this->redirectToRoute('compra_new',['id' => $viaje->getId() ]); // Redirecto a ingreso de tarjeta
        }else{

            $tarjeta = $pasajero->getTarjeta();
            $ticket = new Ticket();
            $form = $this->createForm(TicketType::class, $ticket)
                ->remove('numero')
                ->remove('codigo')
                ->remove('vencimiento');

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if(!$viaje->lleno()){
                    $entityManager = $this->getDoctrine()->getManager();
                    $ticket->setPasajero($pasajero);
                    $ticket->setViaje($viaje);
                    $ticket->setPrecio($viaje->getPrecio());
                    $ticket->setTarjeta($tarjeta); //set tarjeta
                    $entityManager->persist($ticket);
                    $entityManager->flush();
                    return $this->redirectToRoute('terceroWithTicket_new',['id' => $ticket->getId() ]); // Redireccionar a compra de terceros
                }else{
                    $this->addFlash('failed','No quedan asientos disponibles para este viaje');
                }
    
                
            }
            return $this->render('ticket/_new_tarjeta_.html.twig', [
                'tarjeta' => $tarjeta,
                'viaje' => $viaje,
                'form' => $form->createView(),
            ]);

        }
        
    }

    /**
     * @Route("/{id}/new", name="compra_new", methods={"GET","POST"})
     */
    public function builde(Request $request, Viaje $viaje): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);
        
       
        $repository=$this->getDoctrine()->getRepository(Pasajero::class);
        $pasajero= $repository->findOneBy(['user' =>  $this->getUser()->getId() ]);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!$viaje->lleno()){
                $entityManager = $this->getDoctrine()->getManager();
                $ticket->setPasajero($pasajero);
                $ticket->setViaje($viaje);
                $ticket->setPrecio($viaje->getPrecio()); 
                $entityManager->persist($ticket);
                $entityManager->flush();
                return $this->redirectToRoute('terceroWithTicket_new',['id' => $ticket->getId() ]); // Redireccionar a compra de terceros
            }else{
                $this->addFlash('failed','No quedan asientos disponibles para este viaje');
            }

            
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/finalizar", name="compra_finish", methods={"GET"})
     */
    public function finish(Ticket $ticket): Response
    {   
        $this->addFlash('buySuccess','La compra se realizo con exito');
        
        return $this->render('pasajero/show.html.twig', [
            'pasajero' => $ticket->getPasajero(),
        ]);
    }
    

    /**
     * @Route("/{id}", name="ticket_show", methods={"GET"})
     */
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ticket_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ticket $ticket): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticket_index');
        }

        return $this->render('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_delete", methods={"POST"})
     */
    public function delete(Request $request, Ticket $ticket): Response
    {
    
        if($ticket->getViaje()->getEstado()!='Iniciado'){
            
            date_default_timezone_set('America/Buenos_Aires');
            $now = new \DateTime();
            $reembolsoT=($ticket->getPrecioTotal());
            $reembolsoP = ($reembolsoT/2);
            if((($ticket->getViaje()->getSalida())->modify('-2 day'))>$now){
                $this->addFlash('success','Se reembolsara la totalidad del precio del viaje: $'. $reembolsoT);
            }else{
                $this->addFlash('success','Se reembolsara la mitad del precio del viaje: $' . $reembolsoP);
            }

           // dd(mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
           // dd(mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")) <= date("d-m-Y",strtotime($ticket->getViaje()->getSalida()."- 2 days")));
            
            if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                foreach ($ticket->getTerceros() as $tercero){
                    $entityManager->remove($tercero);
                }
                $entityManager->remove($ticket);
                $entityManager->flush();
            }



        }else{
            $this->addFlash('failed', 'El viaje ya inicio, no puede ser cancelado');
        }    
        return $this->redirectToRoute('pasajero_show',['id' => $ticket->getPasajero()->getId() ]);
    }


    /**
     * @Route("/{id}/cancelar", name="ticket_compra_cancelar", methods={"POST"})
     */
    public function cancelarCompra(Request $request, Ticket $ticket): Response
    { 
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($ticket->getTerceros() as $tercero){
                $entityManager->remove($tercero);
            }
            $entityManager->remove($ticket);
            $entityManager->flush();
        }
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/{id}/cobrar", name="ticket_cobrar", methods={"POST"})
     */
    public function reembolsar(Ticket $ticket, TerceroRepository $terceroRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $terceros = $terceroRepository->findBy(['ticket' => $ticket->getId()]);

        foreach($terceros as $tercero){ //Seter que se combraron todos los pasajes de terceros
            $tercero->setCobro(true);
            $entityManager->persist($tercero);
        }

        $ticket->setCobro(true);
        $entityManager->persist($ticket);
        $entityManager->flush();

        return $this->redirectToRoute('ticket_index');
    }

}
