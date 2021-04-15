<?php

namespace App\Form;

use App\Entity\Visit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('description')
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => $options['btn-label'],
                    'attr' => ['class' => 'btn btn-outline-warning mt-3']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'btn-label' => "Zapisz"
            ]
        );
    }
}
