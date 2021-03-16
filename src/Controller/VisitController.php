<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Visit;
use App\Form\VisitType;
use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitController extends AbstractController
{
    /**
     * @var VisitRepository
     */
    private $visitRepository;

    public function __construct(VisitRepository $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

    /**
     * @Route("/animal/{id}/visit", name="visit")
     * @param Animal $animal
     * @return Response
     */
    public function index(Animal $animal): Response
    {
        $visits = $this->visitRepository->findBy(['animal' => $animal]);

        return $this->render(
            'visit/index.html.twig',
            [
                'visits' => $visits,
                'animal' => $animal
            ]
        );
    }

    /**
     * @Route("/animal/{id}/visit/create", name="create_visit")
     * @param Animal $animal
     * @param Request $request
     * @return Response
     */
    public function create(Animal $animal, Request $request): Response
    {
        $visit = new Visit();
        $form = $this->createForm(VisitType::class, $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $visit->setAnimal($animal);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visit);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Dodano nową wizytę!'
            );

            return $this->redirectToRoute(
                'visit',
                [
                    'id' => $animal->getId()
                ]
            );
        }

        return $this->render(
            'visit/create.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $animal
            ]
        );
    }
}
