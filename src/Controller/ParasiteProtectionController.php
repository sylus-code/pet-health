<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Prevention;
use App\Form\ParasiteProtectionType;
use App\Repository\PreventionRepository;
use App\Security\PreventionVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParasiteProtectionController extends AbstractController
{
    private $preventionRepository;

    public function __construct(PreventionRepository $preventionRepository)
    {
        $this->preventionRepository = $preventionRepository;
    }

    /**
     * @Route("/animal/{id}/parasite-protection", name="parasite_protection")
     * @param Animal $animal
     * @return Response
     */
    public function index(Animal $animal): Response
    {
        $parasiteProtections = $this->preventionRepository->findBy(
            [
                'type' => Prevention::PARASITE_PROTECTION,
                'animal' => $animal
            ]
        );
        $this->denyAccessUnlessGranted(PreventionVoter::ACCESS,$parasiteProtections);

        return $this->render(
            'parasite-protection/index.html.twig',
            [
                'parasiteProtections' => $parasiteProtections,
                'animal' => $animal
            ]
        );
    }

    /**
     * @param Animal $animal
     * @param Request $request
     * @return Response
     * @Route("/animal/{id}/parasite-protection/create", name="create_parasite_protection")
     */
    public function create(Animal $animal, Request $request): Response
    {
        $parasiteProtection = new Prevention();

        $form = $this->createForm(ParasiteProtectionType::class, $parasiteProtection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parasiteProtection->setAnimal($animal);
            $parasiteProtection->setType(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($parasiteProtection);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Dodano nowe zabezpieczenie na pasożyty!'
            );

            return $this->redirectToRoute('parasite_protection', ['id' => $animal->getId()]);
        }

        return $this->render(
            'parasite-protection/create.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $animal
            ]
        );
    }

    /**
     * @param Prevention $parasiteProtection
     * @param Animal $animal
     * @param Request $request
     * @return Response
     * @Route("/animal/{animalId}/parasite-protection/{parasiteProtectionId}/edit", name="edit_parasite_protection")
     * @ParamConverter("parasiteProtection", class="App\Entity\Prevention", options={"id" = "parasiteProtectionId"})
     * @ParamConverter("animal", class="App\Entity\Animal", options={"id" = "animalId"})
     */
    public function update(Prevention $parasiteProtection, Animal $animal, Request $request): Response
    {
        $this->denyAccessUnlessGranted(PreventionVoter::ACCESS, $parasiteProtection);

        $form = $this->createForm(ParasiteProtectionType::class, $parasiteProtection);
        $form->handleRequest($request);

        if (!$parasiteProtection) {
            $this->addFlash(
                'warning',
                'Zabezpieczenie na pasożyty o podanym id: ' . $parasiteProtection->getId() . ' nie istnieje!'
            );
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($parasiteProtection);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Zabezpieczenie na pasożyty zostało zaktualizowane!'
            );

            return $this->redirectToRoute(
                'edit_parasite_protection',
                [
                    'animalId' => $animal->getId(),
                    'parasiteProtectionId' => $parasiteProtection->getId()
                ]
            );
        }

        return $this->render(
            'parasite-protection/edit.html.twig',
            [
                'form' => $form->createView(),
                'animal' => $animal
            ]
        );
    }

    /**
     * @param Prevention $parasiteProtection
     * @param Animal $animal
     * @return Response
     * @Route("/animal/{animalId}/parasite-protection/{parasiteProtectionId}/delete", name="delete_parasite_protection")
     * @ParamConverter("parasiteProtection", class="App\Entity\Prevention", options={ "id" = "parasiteProtectionId"})
     * @ParamConverter("animal", class="App\Entity\Animal", options={ "id" = "animalId"})
     */
    public function delete(Prevention $parasiteProtection, Animal $animal): Response
    {
        $this->denyAccessUnlessGranted(PreventionVoter::ACCESS, $parasiteProtection);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($parasiteProtection);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Zabezpieczenia na pasożyty zostało usunięte!'
        );

        return $this->redirectToRoute(
            'parasite_protection',
            [
                'id' => $animal->getId()
            ]
        );
    }
}
