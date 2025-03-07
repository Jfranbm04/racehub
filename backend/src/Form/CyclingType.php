<?php

namespace App\Form;

use App\Entity\Cycling;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CyclingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('date', DateType::class)
            ->add('distanceKm', NumberType::class)
            ->add('location', TextType::class)
            ->add('coordinates', TextType::class)
            ->add('unevenness', NumberType::class)
            ->add('entryFee', NumberType::class)
            ->add('availableSlots', NumberType::class)
            ->add('category', TextType::class)
            ->add('image', TextType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'm',
                    'Female' => 'f'
                ],
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cycling::class,
        ]);
    }
}