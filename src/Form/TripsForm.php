<?php

namespace App\Form;

use App\Entity\Route as rte;
use App\Entity\Trips;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('trip_span')
            ->add('price')
            ->add('spots_number')
            ->add('start_date')
            ->add('route', EntityType::class, [
                'class' => rte::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trips::class,
        ]);
    }
}
