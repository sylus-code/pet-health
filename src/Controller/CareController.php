<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Prevention;
use App\Form\CareType;
use App\Repository\PreventionRepository;
use App\Security\PreventionVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CareController extends AbstractController
{
    private $preventionRepository;

    public function __construct(PreventionRepository $preventionRepository)
    {
        $this->preventionRepository = $preventionRepository;
    }

    /**
     * @Route("/animal/{id}/care", name="care")
     * @param Animal $animal
     * @return Response
     */
    public function index(Animal $animal): Response
    {
        $cares = $this->preventionRepository->findBy(
            [
                'type' => Prevention::CARE,
                'animal' => $animal
            ]
        );

        $this->denyAccessUnlessGranted(PreventionVoter::ACCESS, $cares);

        return $this->render(
            'care/index.html.twig',
            [
                'cares' => $cares,
                'animal' => $animal,
                'currentTab' => 'cares'
            ]
        );
    }

    /**
     * @param Prevention $care
     * @param Request $request
     * @return Response
     * @Route("/care/{id}/edit", name="edit_care")
     */
    public function update(Prevention $care, Request $request): Response
    {
        $this->denyAccessUnlessGranted(PreventionVoter::ACCESS, $care);

        $form = $this->createForm(CareType::class, $care);
        $form->handleRequest($request);

        if (!$care) {
            $this->addFlash('warning', 'Pielęgnacja o podanym id: ' . $care->getId() . ' nie istnieje!');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->preventionRepository->save($care);

            $this->addFlash(
                'success',
                'Pielęgnacja została zaktualizowana!'
            );

            return $this->redirectToRoute(
                'edit_care',
                [
                    'id' => $care->getId()
                ]
            );
        }

        return $this->render(
            'care/edit.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $care->getAnimal()
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
            $care->setAnimal($animal);
            $care->setType(Prevention::CARE);
            $this->preventionRepository->save($care);

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
     * @param Prevention $care
     * @return Response
     * @Route("/care/{id}/delete", name="delete_care")
     */
    public function delete(Prevention $care): Response
    {
        $this->denyAccessUnlessGranted(PreventionVoter::ACCESS, $care);
        $this->preventionRepository->delete($care);

        $this->addFlash(
            'success',
            'Pielęgnacja została usunięta!'
        );

        return $this->redirectToRoute(
            'care',
            [
                'id' => $care->getAnimal()->getId()
            ]
        );
    }
}
