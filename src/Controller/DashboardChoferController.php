<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Chofer;
use App\Entity\Combi;
use App\Entity\Viaje;
use Laminas\EventManager\ResponseCollection;

class DashboardChoferController extends AbstractController
{
    /**
     * @Route("/dashboard/chofer", name="dashboard_chofer")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Chofer::class);
        $chofer = $repository->findOneBy(['user' => $this->getUser()->getId()]);
        $repCombi = $this->getDoctrine()->getRepository(Combi::class);
        $combi= $repCombi->findOneBy(['chofer' => $chofer->getId()]);
        $repViajes = $this->getDoctrine()->getRepository(Viaje::class);
        $viajes = $repViajes->findBy(['combi' => $combi->getId(), 'estado' => 'Finalizado']);
        $viajes2 = $repViajes->findBy(['combi' => $combi->getId(), 'estado' => 'No Iniciado']);
        if ($viajes2){
        $uViaje = $repViajes->ultimoViajeDe($combi);
        }
        else
        {
            $uViaje = NULL;
        }
        return $this->render('dashboard_chofer/index.html.twig', [
            'controller_name' => 'DashboardChoferController',
            'chofer' => $chofer,
            'viajes' => $viajes,
            'uViaje' => $uViaje
        ]);
    }

    /**
     * @Route("/dashboard/{id}/viaje", name="dashboard_ver_viaje", methods={"GET"} )
     */
    public function verViaje(Viaje $viaje): Response
    {
        return $this->render('dashboard_chofer/viaje.html.twig',[
            'viaje' => $viaje
        ]);
    }

}
