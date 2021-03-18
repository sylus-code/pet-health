<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\SymptomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/animal/{id}//symptom", name="symptom")
     */
    public function index(Animal $animal): Response
    {
        $symptoms = $this->symptomRepository->findBy(['animal' => $animal]);
        return $this->render(
            'symptom/index.html.twig',
            [
                'symptoms' => $symptoms,
                'animal' => $animal
            ]
        );
    }
}
