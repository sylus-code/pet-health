<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Symptom;
use App\Form\SymptomType;
use App\Repository\SymptomRepository;
use App\Security\SymptomVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @param Animal $animal
     * @return Response
     */
    public function index(Animal $animal): Response
    {
        $symptoms = $this->symptomRepository->findBy(['animal' => $animal]);
        $this->denyAccessUnlessGranted(SymptomVoter::ACCESS, $symptoms);

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

        if ($form->isSubmitted() && $form->isValid()) {
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

    /**
     * @param Symptom $symptom
     * @param Request $request
     * @return Response
     * @Route ("/animal/{animalId}/symptom/{symptomId}/edit", name="edit_symptom")
     * @ParamConverter("animal", class="App\Entity\Animal", options={"id" = "animalId"})
     * @ParamConverter("symptom", class="App\Entity\Symptom", options={"id" = "symptomId"})
     */
    public function update(Symptom $symptom, Request $request): Response
    {
        $this->denyAccessUnlessGranted(SymptomVoter::ACCESS, $symptom);
        $form = $this->createForm(SymptomType::class, $symptom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($symptom);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Aktualizowano niepokojący objaw!'
            );

            return $this->redirectToRoute(
                'edit_symptom',
                [
                    'animalId' => $symptom->getAnimal()->getId(),
                    'symptomId' => $symptom->getId()
                ]
            );
        }

        return $this->render('symptom/edit.html.twig', [
            'form' => $form->createView(),
            'animal' => $symptom->getAnimal()
        ]);
    }

    /**
     * @param Symptom $symptom
     * @return Response
     * @Route("/symptom/{id}/delete", name="delete_symptom")
     */
    public function delete(Symptom $symptom): Response
    {
        $this->denyAccessUnlessGranted(SymptomVoter::ACCESS, $symptom);
        $animal = $symptom->getAnimal();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($symptom);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Niepokojący objaw został usunięty!'
        );

        return $this->redirectToRoute('symptom',[
            'id' => $animal->getId()
        ]);
    }
}
