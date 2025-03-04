<?php

namespace App\Form;

use App\Entity\TrailRunning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrailRunningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('distance_km')
            ->add('location')
            ->add('coordinates')
            ->add('unevenness')
            ->add('entry_fee')
            ->add('available_slots')
            ->add('status')
            ->add('category')
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrailRunning::class,
        ]);
    }
}
