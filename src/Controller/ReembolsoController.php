<?php

namespace App\Controller;

use App\Entity\Tercero;
use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReembolsoController extends AbstractController
{
    /**
     * @Route("/reembolso", name="reembolso")
     */
    public function index(): Response
    {   
        $repositoryTicket=$this->getDoctrine()->getRepository(Ticket::class );
        $ticketsAReembolsar = $repositoryTicket->findBy(['reembolso'=> false]);
        $ticketsReembolsados = $repositoryTicket->findBy(['reembolso'=> true]);

        $repositoryTercero=$this->getDoctrine()->getRepository(Tercero::class);
        $terceroAReembolsar = $repositoryTercero->findBy(['reembolso'=> false]);
        $terceroReembolsados = $repositoryTercero->findBy(['reembolso'=> true]);

        return $this->render('reembolso/index.html.twig', [
            'controller_name' => 'ReembolsoController',
            'terceroAReembolsar' => $terceroAReembolsar,
            'terceroReembolsados' => $terceroReembolsados,
            'ticketsAReembolsar' => $ticketsAReembolsar,
            'ticketsReembolsados' => $ticketsReembolsados, 
        ]);
    }
}
