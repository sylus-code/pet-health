<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Prevention;
use App\Repository\PreventionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}
