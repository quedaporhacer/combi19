<?php

namespace App\Controller;

use App\Entity\Consumo;
use App\Entity\Ticket;
use App\Entity\Pasajero;
use App\Form\ConsumoType;
use App\Repository\ConsumoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/consumo")
 */
class ConsumoController extends AbstractController
{
    /**
     * @Route("/", name="consumo_index", methods={"GET"})
     */
    public function index(ConsumoRepository $consumoRepository): Response
    {
        return $this->render('consumo/index.html.twig', [
            'consumos' => $consumoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="compra", methods={"GET"})
     */
    public function compra(ConsumoRepository $consumoRepository, Ticket $ticket): Response
    {
        return $this->render('consumo/index.html.twig', [
            'consumos' => $consumoRepository->findBy(['ticket'=> $ticket->getId()]),
            'ticket' => $ticket,
            'pasajero' => $ticket->getPasajero()
        ]);
    }

    /**
     * @Route("/{id}/new", name="consumo_new", methods={"GET","POST"})
     */
    public function new(Request $request, Ticket $ticket) : Response
    {
        $consumo = new Consumo();
        $form = $this->createForm(ConsumoType::class, $consumo);
        $form->remove('precio')->remove('ticket');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form['cantidad']->getData()<=$form['insumo']->getData()->getStock()){
                $entityManager = $this->getDoctrine()->getManager();
                $precio = ($form['cantidad']->getData() * $form['insumo']->getData()->getPrecio());
                $insumo= $form['insumo']->getData();
                $insumo->setStock($insumo->getStock()-$form['cantidad']->getData());
                $consumo->setPrecio($precio);
                $consumo->setTicket($ticket);
                $entityManager->persist($insumo);
                $entityManager->persist($consumo);
                $entityManager->flush();
            }else{
               $this->addFlash('failure','La cantidad que pidio del insumo es mayor al stock actual');
            }


            return $this->redirectToRoute('compra',['id' => $ticket->getId()]);
        }

        return $this->render('consumo/new.html.twig', [
            'consumo' => $consumo,
            'form' => $form->createView(),
            'pasajero' => $ticket->getPasajero(),
            'ticket' => $ticket
        ]);
    }

    /**
     * @Route("/{id}", name="consumo_show", methods={"GET"})
     */
    public function show(Consumo $consumo): Response
    {
        return $this->render('consumo/show.html.twig', [
            'consumo' => $consumo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="consumo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Consumo $consumo): Response
    {
        $form = $this->createForm(ConsumoType::class, $consumo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('consumo_index');
        }

        return $this->render('consumo/edit.html.twig', [
            'consumo' => $consumo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="consumo_delete", methods={"POST"})
     */
    public function delete(Request $request, Consumo $consumo): Response
    {
        $red = $consumo->getTicket();
        if ($this->isCsrfTokenValid('delete'.$consumo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $insumo = $consumo->getInsumo();
            $insumo->setStock($insumo->getStock()+$consumo->getCantidad());
            $entityManager->remove($consumo);
            $entityManager->persist($insumo);
            $entityManager->flush();
        }
        $this->addFlash('failed','La compra fue cancelada correctamente ');
        return $this->redirectToRoute('compra',['id' => $red]);
    }
}
