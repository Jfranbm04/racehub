<?php

namespace App\Form;

use App\Entity\TrailRunning;
use App\Entity\TrailRunningParticipant;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrailRunningParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('time', null, [
                'widget' => 'single_text',
            ])
            ->add('dorsal')
            ->add('banned')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('trailRunning', EntityType::class, [
                'class' => TrailRunning::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrailRunningParticipant::class,
        ]);
    }
}
