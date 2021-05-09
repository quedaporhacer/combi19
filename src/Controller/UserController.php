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
        $formTarjeta = $this->createForm(TarjetaType::class, $tarjeta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email= $form->getData()->getEmail();
            $repository=$this->getDoctrine()->getRepository(User::class);
            $otherUser= $repository->findOneBy(['email' =>  $email ]);
            if(!$otherUser){ 
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($form->getData());
                $entityManager->flush();
                //return $this->redirectToRoute('login');
               
            } else {
                $this->addFlash('success', 'El email ya se encuentra registrado!');
            }
        }
        return $this->render('user/register.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'formTarjeta' => $formTarjeta->createView(),
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
