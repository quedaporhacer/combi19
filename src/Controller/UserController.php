<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email= $form->getData()->getEmail();
            $repository=$this->getDoctrine()->getRepository(User::class);
            $otherUser= $repository->findOneBy(['email' =>  $email ]);
            if(!$otherUser){ 
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($form->getData());
                $entityManager->flush();  
                $this->addFlash('success', 'Se registro correctamente!');
            }
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()
        ]);
    }
}
