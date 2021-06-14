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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


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
        $form = $this->createFormBuilder(null)
            ->add('origen')
            ->add('destino')
            ->add('salida', DateType::class, 
            ['widget' => 'single_text']
            )
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd('gola');
            
            $origen=$form['origen']->getData();
            $destino=$form['destino']->getData();
            $salida=$form['salida']->getData();

            $repository = $this->getDoctrine()->getRepository(Viaje::class);
           
            $viajes = $repository->findbyRutaySalida($origen,$destino,$salida);

            $i=0;
            $arr=[];
            foreach ($viajes as $id) {
                $arr[$i] = $repository->find($id);
                $i=$i+1;
            }
            return $this->redirectToRoute('dashboard_resultados',['viajes '=>$arr]);

            /*$repository = $this->getDoctrine()->getRepository(Ruta::class);
            $rutas= $repository->findBy(['destino' => ($form['ruta'])['destino']->getData(), 'origen' => ($form['ruta'])['origen']->getData() ]);
            $repository = $this->getDoctrine()->getRepository(Viaje::class);
            foreach ($rutas as $ruta){
            $viajes = $viajes + $repository->findBy(['ruta' => $ruta, 'salida'=>$form['salida']->getData()]);
            }*/
            
        }
        return $this->render('dashboard/search.html.twig', [
            'form' => $form->createView(),
        //    'viajes' => $arr,
        ]);
    }

    /**
     * @Route("/dashboard/{viajes}/busqueda", name="dashboard_resultados", methods={"GET"})
     */
    public function show(array $viajes): Response
    {
        
        return $this->render('dashboard/resultados.html.twig', [
            'viajes' => $viajes,
        ]);
    }    
}
