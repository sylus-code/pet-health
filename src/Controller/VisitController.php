<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitController extends AbstractController
{
    /**
     * @Route("/animal/{id}/visit", name="visit")
     * @param Animal $animal
     * @param VisitRepository $visitRepository
     * @return Response
     */
    public function index(Animal $animal, VisitRepository $visitRepository): Response
    {
        $visits = $visitRepository->findBy(['animal' => $animal]);
        return $this->render(
            'visit/index.html.twig',
            [
                'visits' => $visits,
                'animal' => $animal
            ]
        );
    }
}
