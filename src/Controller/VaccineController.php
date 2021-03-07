<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Prevention;
use App\Form\VaccineType;
use App\Repository\PreventionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VaccineController extends AbstractController
{
    /**
     * @Route("/animal/{id}/vaccine", name="vaccine")
     */
    public function index(Animal $animal, PreventionRepository $preventionRepository): Response
    {
        $vaccines = $preventionRepository->findBy(['type'=> Prevention::VACCINE]);

        return $this->render('vaccine/index.html.twig', [
            [],
            'animal' => $animal
        ]);
    }

    /**
     * @Route("/vaccine/create", name="create_vaccine")
     */
    public function create(Request $request): Response
    {
        $vaccine = new Prevention();
        $form = $this->createForm(VaccineType::class, $vaccine);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $vaccine->setType(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vaccine);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Dodano nowe szczepienie!'
            );

            return $this->redirectToRoute('vaccine');
        }
        return $this->render('vaccine/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
