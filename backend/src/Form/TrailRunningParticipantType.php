<?php

namespace App\Form;

use App\Entity\TrailRunning;
use App\Entity\TrailRunningParticipant;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrailRunningParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dorsal', IntegerType::class, [
                'required' => true,
                'label' => 'Dorsal'
            ])
            ->add('banned', CheckboxType::class, [
                'required' => false,
                'label' => 'Banned'
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => 'User'
            ])
            ->add('trailRunning', EntityType::class, [
                'class' => TrailRunning::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => 'Trail Running Event'
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
