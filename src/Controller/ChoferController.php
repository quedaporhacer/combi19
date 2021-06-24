<?php

namespace App\Controller;

use App\Entity\Chofer;
use App\Entity\Combi;
use App\Form\ChoferType;
use App\Repository\ChoferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/chofer")
 */
class ChoferController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="chofer_index", methods={"GET"})
     */
    public function index(ChoferRepository $choferRepository): Response
    {
        return $this->render('chofer/index.html.twig', [
            'chofers' => $choferRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="chofer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $chofer = new Chofer();
        $form = $this->createForm(ChoferType::class, $chofer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $chofer->getUser()->setRoles(["ROLE_CHOFER"]);
            $chofer->getUser()->setPassword($this->passwordEncoder->encodePassword( $chofer->getUser(),
            ($form['user'])['password']->getData()));
            $entityManager->persist($chofer);
            $entityManager->flush();

            return $this->redirectToRoute('chofer_index');
        }

        return $this->render('chofer/new.html.twig', [
            'chofer' => $chofer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chofer_show", methods={"GET"})
     */
    public function show(Chofer $chofer): Response
    {
        return $this->render('chofer/show.html.twig', [
            'chofer' => $chofer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="chofer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Chofer $chofer): Response
    {
        $form = $this->createForm(ChoferType::class, $chofer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chofer->getUser()->setPassword($this->passwordEncoder->encodePassword( $chofer->getUser(),
            ($form['user'])['password']->getData()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('chofer_index');
        }

        return $this->render('chofer/edit.html.twig', [
            'chofer' => $chofer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chofer_delete", methods={"POST"})
     */
    public function delete(Request $request, Chofer $chofer): Response
    {
        $repository = $this->getDoctrine()->getRepository(Combi::class);
        $combi = $repository->findOneBy(['chofer' =>  $chofer ]);

        if ( !($combi ) ){
        
            if ($this->isCsrfTokenValid('delete'.$chofer->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($chofer);
                $entityManager->flush();
            }

        } else {

            $this->addFlash('failed', 'El chofer se encuentra asociado a una combi!');

        }

        return $this->redirectToRoute('chofer_index');
    }
}
