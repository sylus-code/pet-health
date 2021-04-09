<?php

namespace App\Form;

use App\Entity\Animal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\UnicodeString;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Imię zwierzaka: '])
            ->add(
                'sex',
                ChoiceType::class,
                ['label' => 'Płeć zwierzaka: ', 'choices' => [ '♀' => true, '♂' => false]]
            )
            ->add('dateOfBirth', BirthdayType::class, ['label' => 'Data urodzenia zwierzaka: '])
            ->add('species', TextType::class, ['label' => 'Gatunek zwierzaka: '])
            ->add('breed', TextType::class, ['label' => 'Rasa zwierzaka: '])
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => $options['btn-label'],
                    'attr' => ['class'=>  'btn btn-outline-warning mt-3']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'btn-label' => "⊕Dodaj zwierzaka"
            ]
        );
    }
}
