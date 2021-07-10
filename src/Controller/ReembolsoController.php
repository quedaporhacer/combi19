<?php

namespace App\Controller;

use App\Entity\Tercero;
use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/reembolso")
     */
class ReembolsoController extends AbstractController
{
    /**
     * @Route("/", name="reembolso_index")
     */
    public function index(): Response
    {   
        $repositoryTicket=$this->getDoctrine()->getRepository(Ticket::class );
        $ticketsAReembolsar = $repositoryTicket->findBy(['reembolso'=> false,'cobro' => true]);
        $ticketsReembolsados = $repositoryTicket->findBy(['reembolso'=> true]);

        $repositoryTercero=$this->getDoctrine()->getRepository(Tercero::class);
        $terceroAReembolsar = $repositoryTercero->findBy(['reembolso'=> false, 'cobro' => true]);
        $terceroReembolsados = $repositoryTercero->findBy(['reembolso'=> true]);

        return $this->render('reembolso/index.html.twig', [
            'controller_name' => 'ReembolsoController',
            'terceroAReembolsar' => $terceroAReembolsar,
            'terceroReembolsados' => $terceroReembolsados,
            'ticketsAReembolsar' => $ticketsAReembolsar,
            'ticketsReembolsados' => $ticketsReembolsados, 
        ]);
    }

     /**
     * @Route("/{id}/resolve", name="reembolsar", methods={"POST"})
     */
    public function reembolsar(Ticket $ticket): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ticket->setReembolso(true);
        $entityManager->persist($ticket);
        $entityManager->flush();

        return $this->redirectToRoute('reembolso_index');
    }

    /**
     * @Route("/tercero/{id}/resolve", name="reembolsar_tercero", methods={"POST"})
     */
    public function reembolsarTercero(Tercero $tercero): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tercero->setReembolso(true);
        $entityManager->persist($tercero);
        $entityManager->flush();

        return $this->redirectToRoute('reembolso_index');
    }
}
