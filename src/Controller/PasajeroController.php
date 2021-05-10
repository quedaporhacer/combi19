<?php

namespace App\Controller;

use App\Entity\Pasajero;
use App\Form\PasajeroType;
use App\Entity\Tarjeta;
use App\Form\TarjetaType;
use App\Repository\PasajeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/pasajero")
 */
class PasajeroController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home(): Response
    {
        return $this->render('pasajero/index.html.twig', [
            'controller_name' => 'PasajeroController',
        ]);
    }

    /**
     * @Route("/", name="pasajero_index", methods={"GET"})
     */
    public function index(PasajeroRepository $pasajeroRepository): Response
    {
        return $this->render('pasajero_crud/index.html.twig', [
            'pasajeros' => $pasajeroRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pasajero_new")
     */
    public function register(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $pasajero = new Pasajero();
        $form = $this->createForm(PasajeroType::class, $pasajero);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email= $form->getData()->getEmail();
            $repository=$this->getDoctrine()->getRepository(Pasajero::class);
            $otherUser= $repository->findOneBy(['email' =>  $email ]);
            if(!$otherUser){
                

                $pasajero->setPassword($passwordEncoder->encodePassword(
                            $pasajero,
                            $form->getData()->getPassword()
                ));

                $pasajero->setRoles(['ROLE_USER']);
                $pasajero->setPlan(false);      
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($pasajero);
                $entityManager->flush();
                return $this->redirectToRoute('gold_new', array('id' => $pasajero->getId()));  
               
            } else {
                $this->addFlash('success', 'El email ya se encuentra registrado!');
            }
        }
        return $this->render('pasajero/new.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/gold/{id}", name="gold_new")
     */
    public function gold(Request $request,Pasajero $pasajero,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $tarjeta = new Tarjeta();
        $form = $this->createForm(TarjetaType::class, $tarjeta);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            
            $tarjeta->setPropietario($pasajero);
            $pasajero->setPlan(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tarjeta);
            $entityManager->persist($pasajero);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');  
           
        }
        return $this->render('pasajero/membresia.html.twig', [
            'controller_name' => $pasajero,
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/{id}", name="pasajero_delete", methods={"POST"})
     */
    public function delete(Request $request, Pasajero $pasajero): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pasajero->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pasajero);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pasajero_index');
    }
}
