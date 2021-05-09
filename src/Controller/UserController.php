<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Tarjeta;
use App\Form\TarjetaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    /**
     * @Route("/user/new", name="newuser")
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $tarjeta = new Tarjeta();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email= $form->getData()->getEmail();
            $repository=$this->getDoctrine()->getRepository(User::class);
            $otherUser= $repository->findOneBy(['email' =>  $email ]);
            if(!$otherUser){
                
                $user->setMembresia(false);    
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('gold');
               
            } else {
                $this->addFlash('success', 'El email ya se encuentra registrado!');
            }
        }
        return $this->render('user/register.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/user/new/gold", name="gold")
     */
    public function gold(Request $request): Response
    {
        $tarjeta = new Tarjeta();
        $form = $this->createForm(TarjetaType::class, $tarjeta);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $tarjeta1= $form->getData()->getNumero();
            $repository=$this->getDoctrine()->getRepository(Tarjeta::class);
            $othertarjeta= $repository->findOneBy(['numero' =>  $tarjeta1 ]);

            if(!$othertarjeta){
                
                $tarjeta->setPropietario($this->getDoctrine()->getRepository(User::class)->find(13));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($tarjeta);
                $entityManager->flush();

               
            } else {
                $this->addFlash('success', 'La tarjeta ya se encuentra registrada!');
            }
        }
        return $this->render('user/membresia.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            
        ]);
    }
    /**
     * @Route("/user", name="users")
     */
    public function index(Request $request): Response
    {   
        $users= $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', ['users' => $users]);
      
    }

    /**
     * @Route("/user/edit/{id}", name="edit_user")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {

        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
  
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
  
        if($form->isSubmitted() && $form->isValid()) {
  
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
  
          return $this->redirectToRoute('users');
        }
  
        return $this->render('user/edit.html.twig', array(
          'form' => $form->createView()
        ));
      }


      /**
     * @Route("/user/delete/{id}", name="delete_user")
     * Method({"GET", "POST"})
     */
    public function delete(Request $request, $id) {

        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
  
        return $this->redirectToRoute('users');
        
  
      }

}
