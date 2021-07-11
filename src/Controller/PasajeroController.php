<?php

namespace App\Controller;

use App\Entity\Pasajero;
use App\Entity\Ticket;
use App\Entity\User;
use App\Entity\Viaje;
use App\Form\PasajeroType;
use App\Form\UserType;
use App\Repository\PasajeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


/**
 * @Route("/pasajero")
 */
class PasajeroController extends AbstractController
{   
     private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    /**
     * @Route("/", name="pasajero_index", methods={"GET"})
     */
    public function index(PasajeroRepository $pasajeroRepository): Response
    {
        return $this->render('pasajero/index.html.twig', [
            'pasajeros' => $pasajeroRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pasajero_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
 
        $pasajero = new Pasajero();

        $form = $this->createForm(PasajeroType::class, $pasajero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $pasajero->getUser()->setRoles(["ROLE_PASAJERO"]);
            $pasajero->getUser()->setPassword($this->passwordEncoder->encodePassword( $pasajero->getUser(),
            ($form['user'])['password']->getData()));
            $entityManager->persist($pasajero); 
            $entityManager->flush();
            $this->addFlash('success', 'Se registro correctamente');
            return $this->redirectToRoute('tarjeta_new', ['id' => $pasajero->getId()] );
        }

        return $this->render('pasajero/new.html.twig', [
            'pasajero' => $pasajero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/new/express", name="pasajero_expres_new", methods={"GET","POST"})
     */
    public function newExpres(Viaje $viaje, Request $request): Response
    {
 
        $pasajero = new Pasajero();
        $form = $this->createForm(PasajeroType::class, $pasajero);
        $form['user']->remove('password');
        $ticket = new Ticket();   
            
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $pasajero->getUser()->setRoles(["ROLE_PASAJERO"]);
            $pasajero->getUser()->setPassword($this->passwordEncoder->encodePassword($pasajero->getUser(),'combi19'));
            $entityManager->persist($pasajero);
            
            $ticket->setPasajero($pasajero);
            $ticket->setViaje($viaje);
            $ticket->setPrecio($viaje->getPrecio());
            $ticket->setCobro(true);
            $entityManager->persist($ticket); 

            $entityManager->flush();
            $this->addFlash('success', 'Se registro correctamente');
            return $this->redirectToRoute('viaje_show',['id' => $viaje->getId()]);
        }

        return $this->render('pasajero/_new_express.html.twig', [
            'pasajero' => $pasajero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pasajero_show", methods={"GET"})
     */
    public function show(Pasajero $pasajero): Response
    {
        
        return $this->render('pasajero/show.html.twig', [
            'pasajero' => $pasajero,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pasajero_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pasajero $pasajero): Response
    {

     /*   if($pasajero->getUser() != $this->getUser() )
        {
            dd();
        }*/

        $form = $this->createForm(PasajeroType::class, $pasajero);
        $form->remove('nacimiento');
        $form->handleRequest($request);
        $id= $pasajero->getId();
        
        if ($form->isSubmitted() && $form->isValid()) {

            $repository=$this->getDoctrine()->getRepository(User::class);
            $email= $repository->findOneBy([
                'email' =>  ($form['user']['email']->getData())
            ]);
            
            if(!$email){

                $pasajero->getUser()->setPassword($this->passwordEncoder->encodePassword( $pasajero->getUser(),
                ($form['user'])['password']->getData()));
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('pasajero_show',['id'=> $id]);

            }else{
                $this->addFlash('failed', 'Email repetido');
            }

                
        }

        return $this->render('pasajero/edit.html.twig', [
            'pasajero' => $pasajero,
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
        /*if($pasajero->getTickets()->isEmpty()){
        }else{
            $this->addFlash('failed', 'el pasajero tiene tickets');
        }*/
        return $this->redirectToRoute('pasajero_index');
    }

}
