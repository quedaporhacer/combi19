<?php

namespace App\Controller;

use App\Entity\Imprevisto;
use App\Entity\Viaje;
use App\Form\ImprevistoType;
use App\Repository\ImprevistoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/imprevisto")
 */
class ImprevistoController extends AbstractController
{
    /**
     * @Route("/", name="imprevisto_index", methods={"GET"})
     */
    public function index(ImprevistoRepository $imprevistoRepository): Response
    {
        return $this->render('imprevisto/index.html.twig', [
            'imprevistos' =>  array_reverse($imprevistoRepository->findBy(['state' => false])),
            'imprevistosResueltos' => array_reverse($imprevistoRepository->findBy(['state' => true]))
        ]);
    }

    /**
     * @Route("/new", name="imprevisto_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $imprevisto = new Imprevisto();
        $form = $this->createForm(ImprevistoType::class, $imprevisto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($imprevisto);
            $entityManager->flush();

            return $this->redirectToRoute('imprevisto_index');
        }

        return $this->render('imprevisto/new.html.twig', [
            'imprevisto' => $imprevisto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="imprevistoWithViaje_new", methods={"GET","POST"})
     */
    public function newWithViaje(Viaje $viaje,Request $request): Response
    {
        $imprevisto = new Imprevisto();
        $form = $this->createForm(ImprevistoType::class, $imprevisto)
            ->remove('viaje');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $imprevisto->setViaje($viaje);
            $entityManager->persist($imprevisto);
            $entityManager->flush();

            return $this->redirectToRoute('imprevisto_index');
        }

        return $this->render('imprevisto/new.html.twig', [
            'imprevisto' => $imprevisto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="imprevisto_show", methods={"GET"})
     */
    public function show(Imprevisto $imprevisto): Response
    {
        return $this->render('imprevisto/show.html.twig', [
            'imprevisto' => $imprevisto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="imprevisto_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Imprevisto $imprevisto): Response
    {
        $form = $this->createForm(ImprevistoType::class, $imprevisto);
        $form->handleRequest($request);

        if(!$imprevisto->getState()){

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('imprevisto_index');
            }
            
        }else{
            $this->addFlash('failed','No pueden editarse imprevisto que ya se resolvieron');
        }
        return $this->render('imprevisto/edit.html.twig', [
            'imprevisto' => $imprevisto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="imprevisto_delete", methods={"POST"})
     */
    public function delete(Request $request, Imprevisto $imprevisto): Response
    {
        
        if(!$imprevisto->getState()){

            if ($this->isCsrfTokenValid('delete'.$imprevisto->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($imprevisto);
                $entityManager->flush();
            }

        }else{
            $this->addFlash('failed','No pueden eliminsar imprevisto que ya se resolvieron');
        }
        

        return $this->redirectToRoute('imprevisto_index');
    }

    /**
     * @Route("/{id}/resolve", name="imprevisto_resolve", methods={"POST"})
     */
    public function resolve(Imprevisto $imprevisto, ImprevistoRepository $imprevistoRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $imprevisto->resolver();
        $entityManager->persist($imprevisto);
        $entityManager->flush();

        return $this->redirectToRoute('imprevisto_index');
    }
}
