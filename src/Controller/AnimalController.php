<?php

namespace App\Controller;


use App\Entity\Animal;
use App\Form\AnimalType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{

//    /**
//     * @Route("/", name="index")
//     */
//    public function index() :Response{
//
//        return $this->render('animalForm.html.twig');
//    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/", name="create_animal")
     */
    public function create(Request $request): Response {
        $animal = new Animal();

        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $animal->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($animal);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Zwierzak dodany!')
            ;

            return $this->redirectToRoute('create_animal');
        }

        return $this->render('animal/create.html.twig', [
            'form'=> $form->createView()
        ]);
    }
}