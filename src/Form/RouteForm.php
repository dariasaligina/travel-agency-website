<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Route as rte;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('program')
            ->add('additional_info')
            ->add('trip_span')
            ->add('duration')
            ->add('route_span')
            ->add('departure_city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'id',
            ])
            ->add('direction', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => rte::class,
        ]);
    }
}
