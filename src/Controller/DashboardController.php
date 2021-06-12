<?php

namespace App\Controller;

use App\Entity\Pasajero;
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

    public function search(Request $request):Response
    {
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Ruta::class);
            $rutas= $repository->findBy(['destino' => ($form['ruta'])['destino']->getData(), 'origen' => ($form['ruta'])['origen']->getData() ]);
            $repository = $this->getDoctrine()->getRepository(Viaje::class);
            foreach ($rutas as $ruta){
            $viajes = $viajes + $repository->findBy(['ruta' => $ruta, 'salida'=>$form['salida']->getData()]);
            }
        }
        return $this->render('dashboard/search.html.twig',[
            'viajes' => $viajes
        ]);
    }
}
