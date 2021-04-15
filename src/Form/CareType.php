<?php

namespace App\Form;

use App\Entity\Prevention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('date')
            ->add('save', SubmitType::class, [
                'label' => $options['btn-label'],
                'attr' => ['class' => 'btn btn-outline-warning mt-3']]);
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
