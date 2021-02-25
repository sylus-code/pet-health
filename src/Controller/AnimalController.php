<?php

namespace App\Controller;


use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    /**
     * @param AnimalRepository $animalRepository
     * @return Response
     * @Route("/", name="index")
     */
    public function index(AnimalRepository $animalRepository): Response
    {
        $animals = $animalRepository->findAll();
        return $this->render('animal/index.html.twig', [
            'animals' => $animals
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/animal/create", name="create_animal")
     */
    public function create(Request $request): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $animal->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($animal);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Zwierzak dodany!');

            return $this->redirectToRoute('index');
        }

        return $this->render('animal/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Animal $animal
     * @return Response
     * @Route("/animal/show/{id}", name="show_animal")
     */
    public function show(Animal $animal): Response
    {
        return $this->render('animal/show.html.twig', [
            'animal' => $animal
        ]);
    }

    /**
     * @param Animal $animal
     * @return Response
     * @Route("/animal/delete/{id}", name="delete_animal")
     */
    public function delete(Animal $animal): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($animal);
        $em->flush();

        $this->addFlash(
            'success',
            'Zwierzak został usunięty'
        );

        return $this->redirectToRoute('index');
    }
}
