<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Pasajero;
use App\Entity\Viaje;
use App\Form\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ComentarioRepository;


class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(ComentarioRepository $comentarioRepository): Response
    {   
        $repository = $this->getDoctrine()->getRepository(Pasajero::class);
        $pasajero = $repository->findOneBy(['user' => $this->getUser()->getId()]);

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'comentarios' => $comentarioRepository->findAll(),
            'pasajero' => $pasajero
        ]);
    }

    /**
     * @Route("/dashboard/busqueda", name="busqueda")
     */
    public function search(Request $request):Response
    {   
      
        $form = $this->createFormBuilder(null)->add('origen')->add('destino')->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            
            $origen=$form['origen']->getData();
            $destino=$form['destino']->getData();
            $repository = $this->getDoctrine()->getRepository(Viaje::class);
           
            $viajes = $repository->findbyRutaySalida($origen,$destino);

            $i=0;
            foreach ($viajes as $id) {
                $arr[$i] = $repository->find($id);
                $i=$i+1;
            }
            //dd($arr);
            /*$repository = $this->getDoctrine()->getRepository(Ruta::class);
            $rutas= $repository->findBy(['destino' => ($form['ruta'])['destino']->getData(), 'origen' => ($form['ruta'])['origen']->getData() ]);
            $repository = $this->getDoctrine()->getRepository(Viaje::class);
            foreach ($rutas as $ruta){
            $viajes = $viajes + $repository->findBy(['ruta' => $ruta, 'salida'=>$form['salida']->getData()]);
            }*/
            
        }
        return $this->render('viaje/new.html.twig', [
            'form' => $form->createView(),
            'viajes' => $arr,
        ]);
    }
}
