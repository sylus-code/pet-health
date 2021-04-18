<?php

namespace App\Tests\Twig;

use App\Entity\Animal;
use App\Twig\AppExtension;
use PHPUnit\Framework\TestCase;

class AppExtensionTest extends TestCase
{
    public function testSymbolReturnsFemaleSymbol()
    {
        $appExtension = new AppExtension();
        $animal = new Animal();
        $animal->setSex(Animal::SEX_FEMALE);
        $this->assertEquals("♀", $appExtension->symbol($animal->getSex()));
    }

    public function testSymbolReturnsMaleSymbol()
    {
        $appExtension = new AppExtension();
        $animal = new Animal();
        $animal->setSex(Animal::SEX_MALE);
        $this->assertEquals("♂", $appExtension->symbol($animal->getSex()));
    }

    public function testSymbolThrowsInvalidArgumentExceptionWhenWrongArgumentPassed()
    {
        $this->expectException(\InvalidArgumentException::class);

        $appExtension = new AppExtension();
        $animal = new Animal();
        $animal->setSex("wrong");

        $appExtension->symbol($animal->getSex());
    }
}
