<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Visit;
use App\Form\VisitType;
use App\Repository\VisitRepository;
use App\Security\VisitVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitController extends AbstractController
{
    private $visitRepository;

    public function __construct(VisitRepository $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

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

        $this->denyAccessUnlessGranted(VisitVoter::ACCESS, $visits);

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

    /**
     * @param Visit $visit
     * @param Animal $animal
     * @param Request $request
     * @return Response
     * @Route("/animal/{animalId}/visit/{visitId}/edit", name="edit_visit")
     * @ParamConverter("animal", class="App\Entity\Animal", options={"id" = "animalId"})
     * @ParamConverter("visit", class="App\Entity\Visit", options={"id" = "visitId"})
     */
    public function update(Visit $visit, Animal $animal, Request $request): Response
    {
        $this->denyAccessUnlessGranted(VisitVoter::ACCESS, $visit);

        $form = $this->createForm(VisitType::class, $visit);
        $form->handleRequest($request);

        if (!$visit) {
            $this->addFlash('warning', 'Wizyta o podanym id: ' . $visit->getId() . ' nie istnieje!');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visit);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Wizyta została zaktualizowana!'
            );

            return $this->redirectToRoute(
                'edit_visit',
                [
                    'animalId' => $animal->getId(),
                    'visitId' => $visit->getId()
                ]
            );
        }

        return $this->render(
            'visit/edit.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $animal
            ]
        );
    }

    /**
     * @param Visit $visit
     * @return Response
     * @Route("/visit/{id}/delete", name="delete_visit")
     */
    public function delete(Visit $visit): Response
    {
        $this->denyAccessUnlessGranted(VisitVoter::ACCESS, $visit);
        $animal = $visit->getAnimal();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($visit);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Wizyta została usunięta!'
        );

        return $this->redirectToRoute(
            'visit',
            [
                'id' => $animal->getId()
            ]
        );
    }
}
