<?php

namespace App\Controller;

use App\Entity\Ruta;
use App\Entity\Viaje;
use App\Form\RutaType;
use App\Repository\RutaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
* @IsGranted("ROLE_ADMIN")
* @Route("/ruta")
*/

class RutaController extends AbstractController
{
    /**
     * @Route("/", name="ruta_index", methods={"GET"})
     */
    public function index(RutaRepository $rutaRepository): Response
    {
        return $this->render('ruta/index.html.twig', [
            'rutas' => $rutaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ruta_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rutum = new Ruta();
        $form = $this->createForm(RutaType::class, $rutum);
        $form->handleRequest($request);

        if($form['origen']->getData() != $form['destino']->getData()){

            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($rutum);
                $entityManager->flush();

                return $this->redirectToRoute('ruta_index');
            }
        }else{
            $this->addFlash('failed', 'El destino y el origen no pueden ser iguales');
        }
        return $this->render('ruta/new.html.twig', [
            'rutum' => $rutum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ruta_show", methods={"GET"})
     */
    public function show(Ruta $rutum): Response
    {
        
        return $this->render('ruta/show.html.twig', [
            'rutum' => $rutum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ruta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ruta $rutum): Response
    {
        $form = $this->createForm(RutaType::class, $rutum);
        $form->handleRequest($request);
        if($form['origen']->getData() != $form['destino']->getData()){

            if ($form->isSubmitted() && $form->isValid()) {

                $repository = $this->getDoctrine()->getRepository(Viaje::class);
                $viaje= $repository->findOneBy(['ruta' =>  $rutum ]);
                
                if(!$viaje){
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('ruta_index');
                }
                $this->addFlash('failed', 'La ruta se encuentra en uso!');
                    
            }
        }else{
            $this->addFlash('failed', 'Origen y destino no pueden ser iguales');
        }

        return $this->render('ruta/edit.html.twig', [
            'rutum' => $rutum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ruta_delete", methods={"POST"})
     */
    public function delete(Request $request, Ruta $rutum): Response
    {
        $repository = $this->getDoctrine()->getRepository(Viaje::class);
        $viaje= $repository->findOneBy(['ruta' =>  $rutum ]);

        if(!$viaje){

            if ($this->isCsrfTokenValid('delete'.$rutum->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($rutum);
                $entityManager->flush();
            }
          
        }else {
            $this->addFlash('failed', 'La ruta se encuentra en uso!');
        }
        return $this->redirectToRoute('ruta_index');
        

        
    }
}
