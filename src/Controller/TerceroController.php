<?php

namespace App\Controller;

use App\Entity\Tercero;
use App\Entity\Ticket;
use App\Form\TerceroType;
use App\Repository\TerceroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tercero")
 */
class TerceroController extends AbstractController
{
    /**
     * @Route("/", name="tercero_index", methods={"GET"})
     */
    public function index(TerceroRepository $terceroRepository): Response
    {
        return $this->render('tercero/index.html.twig', [
            'terceros' => $terceroRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tercero_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tercero = new Tercero();
        $form = $this->createForm(TerceroType::class, $tercero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tercero);
            $entityManager->flush();

            return $this->redirectToRoute('tercero_index');
        }

        return $this->render('tercero/new.html.twig', [
            'tercero' => $tercero,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/{id}/new", name="terceroWithTicket_new", methods={"GET","POST"})
     */
    public function newWithTicket(Request $request, Ticket $ticket): Response
    {
        $tercero = new Tercero();
        $form = $this->createForm(TerceroType::class, $tercero)
            ->remove('testeo')
            ->remove('precio')
            ->remove('ticket');

        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            if($ticket->getViaje()->isUnique($form['dni']->getData())){
                $entityManager = $this->getDoctrine()->getManager();
                $tercero->setTicket($ticket);
                $tercero->setPrecio($ticket->getPrecio());  // {Rancio}
                $entityManager->persist($tercero);
                $entityManager->flush();
                $this->addFlash('success','el invitado ha sido agregado exitosamente');
                return $this->redirectToRoute('terceroWithTicket_new',['id'=> $ticket->getId()]);
            }else{
                $this->addFlash('failed','el dni ya se encuentra en el viaje');
            }                
        }

        return $this->render('tercero/new.html.twig', [
            'tercero' => $tercero,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="tercero_show", methods={"GET"})
     */
    public function show(Tercero $tercero): Response
    {
        return $this->render('tercero/show.html.twig', [
            'tercero' => $tercero,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tercero_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tercero $tercero): Response
    {
        $form = $this->createForm(TerceroType::class, $tercero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tercero_index');
        }

        return $this->render('tercero/edit.html.twig', [
            'tercero' => $tercero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tercero_delete", methods={"POST"})
     */
    public function delete(Request $request, Tercero $tercero): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tercero->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tercero);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tercero_index');
    }
}
