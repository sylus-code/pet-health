<?php

namespace App\Twig;

use App\Entity\Animal;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('symbol', [$this, 'symbol']),
        ];
    }

    public function symbol(string $animalSex): string
    {
        if ($animalSex == Animal::SEX_FEMALE) {
            return "♀";
        } elseif ($animalSex == Animal::SEX_MALE) {
            return "♂";
        }
        throw new \InvalidArgumentException();
    }
}
