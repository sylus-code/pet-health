<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Symptom;
use App\Form\SymptomType;
use App\Repository\SymptomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SymptomController extends AbstractController
{
    private $symptomRepository;

    public function __construct(SymptomRepository $symptomRepository)
    {
        $this->symptomRepository = $symptomRepository;
    }

    /**
     * @Route("/animal/{id}/symptom", name="symptom")
     */
    public function index(Animal $animal): Response
    {
        $symptoms = $this->symptomRepository->findBy(['animal' => $animal]);
        return $this->render(
            'symptom/index.html.twig',
            [
                'symptoms' => $symptoms,
                'animal' => $animal
            ]
        );
    }

    /**
     * @param Animal $animal
     * @param Request $request
     * @return Response
     * @Route ("/animal/{id}/symptom/create", name="create_symptom")
     */
    public function create(Animal $animal, Request $request): Response
    {
        $symptom = new Symptom();

        $form = $this->createForm(SymptomType::class, $symptom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $symptom->setAnimal($animal);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($symptom);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Dodano nowy niepokojący objaw!'
            );
            return $this->redirectToRoute(
                'symptom',
                [
                    'id' => $animal->getId()
                ]
            );
        }
        return $this->render(
            'symptom/create.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $animal
            ]
        );
    }
}
