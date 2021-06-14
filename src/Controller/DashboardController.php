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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_PASAJERO")
 */
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
            
            
            $origen=$form['origen']->getData();
            $destino=$form['destino']->getData();
            $salida=$form['salida']->getData();
            if($origen!=$destino){
                $repository = $this->getDoctrine()->getRepository(Viaje::class);
                $viajes = $repository->findbyRutaySalida($origen,$destino,$salida);
    
                $i=0;
                foreach ($viajes as $id) {
                    $arr[$i] = $repository->find($id);
                    $i=$i+1;
                }
    
                if(!$viajes)
                    $this->addFlash('failed','No se encontraron viajes');
                else    
                    return $this->render('dashboard/search.html.twig', [
                        'form' => $form->createView(),
                        'viajes' => $arr,
                    ]);
            }else{
                $this->addFlash('failed','El origen y el destino no pueden ser iguales');
            }
            
            
        }
        return $this->render('dashboard/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
