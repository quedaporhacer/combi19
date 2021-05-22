<?php

namespace App\Controller;

use App\Entity\Lugar;
use App\Entity\Ruta;
use App\Form\LugarType;
use App\Repository\LugarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lugar")
 */
class LugarController extends AbstractController
{
    /**
     * @Route("/", name="lugar_index", methods={"GET"})
     */
    public function index(LugarRepository $lugarRepository): Response
    {
        return $this->render('lugar/index.html.twig', [
            'lugars' => $lugarRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lugar_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lugar = new Lugar();
        $form = $this->createForm(LugarType::class, $lugar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nombre= $form->getData()->getNombre();
            $repository=$this->getDoctrine()->getRepository(Lugar::class);
            $otherLugar= $repository->findOneBy(['nombre' =>  $nombre ]);
            if (!$otherLugar){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($lugar);
                $entityManager->flush();
                return $this->redirectToRoute('lugar_index');
            }  
            
            $this->addFlash('failed', 'El lugar ya se encuentra registrado!');
            
        }

        return $this->render('lugar/new.html.twig', [
            'lugar' => $lugar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lugar_show", methods={"GET"})
     */
    public function show(Lugar $lugar): Response
    {
        return $this->render('lugar/show.html.twig', [
            'lugar' => $lugar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lugar_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lugar $lugar): Response
    {
        $form = $this->createForm(LugarType::class, $lugar);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $nombre= $form->getData()->getNombre();
            $repository=$this->getDoctrine()->getRepository(Lugar::class);
            $otherLugar= $repository->findOneBy(['nombre' =>  $nombre ]);

            
           

            if (!$otherLugar){

                $repository = $this->getDoctrine()->getRepository(Ruta::class);
                $origen= $repository->findOneBy(['origen' =>  $lugar ]);
                $destino = $repository->findOneBy(['destino' =>  $lugar ]);

                if (!($origen || $destino)) {

                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('lugar_index');

                }else{
                    $this->addFlash('failed', 'Ese lugar se encuentra en uso!');
                }

               

            }else{
            $this->addFlash('failed', 'Ese nombre ya se encuentra registrado!');
         }
        }

        return $this->render('lugar/edit.html.twig', [
            'lugar' => $lugar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lugar_delete", methods={"POST"})
     */
    public function delete(Request $request, Lugar $lugar): Response
    {   
        $repository = $this->getDoctrine()->getRepository(Ruta::class);
        $origen= $repository->findOneBy(['origen' =>  $lugar ]);
        $destino = $repository->findOneBy(['destino' =>  $lugar ]);

        if (!($origen || $destino)) {

            if ($this->isCsrfTokenValid('delete'.$lugar->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($lugar);
                $entityManager->flush();
                
            }
           
        } else {
            $this->addFlash('failed', 'Ese lugar se encuentra en uso!');
        }
        
        return $this->redirectToRoute('lugar_index');
    }
}
