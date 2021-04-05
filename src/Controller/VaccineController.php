<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Prevention;
use App\Form\VaccineType;
use App\Repository\PreventionRepository;
use App\Security\PreventionVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VaccineController extends AbstractController
{
    private $preventionRepository;

    public function __construct(PreventionRepository $preventionRepository)
    {
        $this->preventionRepository = $preventionRepository;
    }

    /**
     * @Route("/animal/{id}/vaccine", name="vaccine")
     * @param Animal $animal
     * @return Response
     */
    public function index(Animal $animal): Response
    {
        $vaccines = $this->preventionRepository->findBy(
            [
                'type' => Prevention::VACCINE,
                'animal' => $animal
            ]
        );
        $this->denyAccessUnlessGranted(PreventionVoter::ACCESS, $vaccines);

        return $this->render(
            'vaccine/index.html.twig',
            [
                'vaccines' => $vaccines,
                'animal' => $animal
            ]
        );
    }

    /**
     * @Route("/animal/{id}/vaccine/create", name="create_vaccine")
     * @param Animal $animal
     * @param Request $request
     * @return Response
     */
    public function create(Animal $animal, Request $request): Response
    {
        $vaccine = new Prevention();
        $form = $this->createForm(VaccineType::class, $vaccine);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vaccine->setAnimal($animal);
            $vaccine->setType(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vaccine);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Dodano nowe szczepienie!'
            );

            return $this->redirectToRoute('vaccine', ['id' => $animal->getId()]);
        }
        return $this->render(
            'vaccine/create.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $animal
            ]
        );
    }

    /**
     * @Route("/vaccine/{id}/edit", name="edit_vaccine")
     * @param Prevention $vaccine
     * @param Request $request
     * @return Response
     */
    public function update(Prevention $vaccine, Request $request): Response
    {
        $this->denyAccessUnlessGranted(PreventionVoter::ACCESS, $vaccine);

        $form = $this->createForm(VaccineType::class, $vaccine);
        $form->handleRequest($request);

        if (!$vaccine) {
            $this->addFlash('warning', 'Szczepienie o podanym id: ' . $vaccine->getId() . ' nie istnieje!');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vaccine);
            $em->flush();

            $this->addFlash('success', 'Szczepienie zaktualizowane!');
            return $this->redirectToRoute(
                'edit_vaccine',
                [
                    'id' => $vaccine->getId()
                ]
            );
        }
        return $this->render(
            'vaccine/edit.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $vaccine->getAnimal()
            ]
        );
    }

    /**
     * @param Prevention $vaccine
     * @return Response
     * @Route("/vaccine/{id}/delete", name="delete_vaccine")
     */
    public function delete(Prevention $vaccine): Response
    {
        $this->denyAccessUnlessGranted(PreventionVoter::ACCESS, $vaccine);

        $em = $this->getDoctrine()->getManager();
        $em->remove($vaccine);
        $em->flush();

        $this->addFlash(
            'success',
            'Szczepienie zostało usunięte'
        );

        return $this->redirectToRoute(
            'vaccine',
            [
                'id' => $vaccine->getAnimal()->getId()
            ]
        );
    }
}
