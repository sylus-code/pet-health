<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Prevention;
use App\Form\CareType;
use App\Repository\PreventionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CareController extends AbstractController
{
    /**
     * @Route("/animal/{id}/care", name="care")
     */
    public function index(Animal $animal, PreventionRepository $preventionRepository): Response
    {
        $cares = $preventionRepository->findBy(['type' => Prevention::CARE]);
        return $this->render(
            'care/index.html.twig',
            [
                'cares' => $cares,
                'animal' => $animal
            ]
        );
    }

    /**
     * @param Animal $animal
     * @param Request $request
     * @return Response
     * @Route("/animal/{id}/care/create", name="create_care")
     */
    public function create(Animal $animal, Request $request): Response
    {
        $care = new Prevention();
        $form = $this->createForm(CareType::class, $care);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $care->setType(Prevention::CARE);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($care);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Pielęgnacja została dodana!'
            );

            return $this->redirectToRoute(
                'care',
                [
                    'id' => $animal->getId()
                ]
            );
        }

        return $this->render(
            'care/create.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $animal
            ]
        );
    }

    /**
     * @return Response
     * @Route("/animal/{animalId}/care/{careId}/edit", name="edit_care")
     * @ParamConverter("care", class="App\Entity\Prevention", options={ "id" = "careId" })
     * @ParamConverter("animal", class="App\Entity\Animal", options={ "id" = "animalId"})
     */
    public function update(Prevention $care, Animal $animal, Request $request): Response
    {
        $form = $this->createForm(CareType::class, $care);
        $form->handleRequest($request);

        if (!$care) {
            $this->addFlash('warning', 'Pielęgnacja o podanym id: ' . $vaccine->getId() . ' nie istnieje!');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($care);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Pielęgnacja została zaktualizowana!'
            );

            return $this->redirectToRoute(
                'edit_care',
                [
                    'animalId' => $animal->getId(),
                    'careId' => $care->getId()
                ]
            );
        }

        return $this->render(
            'care/edit.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $animal
            ]
        );
    }
}
