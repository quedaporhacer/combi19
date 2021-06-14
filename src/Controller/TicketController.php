<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Viaje;
use App\Form\TicketType;
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
        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="ticket_new", methods={"GET","POST"})
     */
    public function new(Request $request, Viaje $viaje): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ticket->setViaje($viaje);
            $ticket->setPrecio($viaje->getPrecio());
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('ticket_index');
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
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
                $entityManager->remove($ticket);
                $entityManager->flush();
            }



        }else{
            $this->addFlash('failed', 'El viaje ya inicio, no puede ser cancelado');
        }    
        return $this->redirectToRoute('pasajero_show',['id' => $ticket->getPasajero()->getId() ]);
    }
}
