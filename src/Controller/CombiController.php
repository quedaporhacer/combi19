<?php

namespace App\Controller;

use App\Entity\Combi;
use App\Entity\Viaje;
use App\Form\CombiType;
use App\Repository\CombiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/combi")
 */
class CombiController extends AbstractController
{
    /**
     * @Route("/", name="combi_index", methods={"GET"})
     */
    public function index(CombiRepository $combiRepository): Response
    {
        return $this->render('combi/index.html.twig', [
            'combis' => $combiRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="combi_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $combi = new Combi();
        $form = $this->createForm(CombiType::class, $combi);
        $form->handleRequest($request);

       

        if ($form->isSubmitted() && $form->isValid()) {

            $patente= $form->getData()->getPatente();
            $repository=$this->getDoctrine()->getRepository(Combi::class);
            $otherCombi= $repository->findOneBy(['patente' =>  $patente ]);

            if(!$otherCombi){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($combi);
                $entityManager->flush();
                return $this->redirectToRoute('combi_index');
            }   
            $this->addFlash('failed', 'La patente ya se encuentra registrada!');
        }

        return $this->render('combi/new.html.twig', [
            'combi' => $combi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="combi_show", methods={"GET"})
     */
    public function show(Combi $combi): Response
    {
        return $this->render('combi/show.html.twig', [
            'combi' => $combi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="combi_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Combi $combi): Response
    {
        $patenteActual= $combi->getPatente();
        $form = $this->createForm(CombiType::class, $combi);
        /*$form->remove('patente')->remove('modelo')->remove('capacidad')->remove('calidad');*/
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $patente= $form->getData()->getPatente();
            $repository=$this->getDoctrine()->getRepository(Combi::class);
            $otherCombi= $repository->findOneBy(['patente' =>  $patente ]);

            $repository = $this->getDoctrine()->getRepository(Viaje::class);
            $viajesNoIniciados= $repository->findBy(['combi' =>  $combi, 'estado' => 'No iniciado' ]);
            $viajesIniciados= $repository->findBy(['combi' =>  $combi, 'estado' => 'En curso' ]);
            
            if(!$viajesNoIniciados && !$viajesIniciados ){
                if(!$otherCombi || $patente == $patenteActual){
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('combi_index');
                }
                $this->addFlash('failed','La patente ya se encuentra registrada!');
            }else{
            $this->addFlash('failed', 'La combi tiene viajes pendientes, o en curso');
            }
        }

        return $this->render('combi/edit.html.twig', [
            'combi' => $combi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="combi_delete", methods={"POST"})
     */
    public function delete(Request $request, Combi $combi): Response
    {
        
        $repository = $this->getDoctrine()->getRepository(Viaje::class);
        $viaje= $repository->findOneBy(['combi' =>  $combi ]);    
        if(!$viaje){
            if ($this->isCsrfTokenValid('delete'.$combi->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($combi);
                $entityManager->flush();
            }
        } else {
            $this->addFlash('failed', 'La combi se encuentra en uso');
        }
        return $this->redirectToRoute('combi_index');
    }
}
