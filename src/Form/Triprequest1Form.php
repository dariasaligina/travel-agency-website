<?php

namespace App\Form;

use App\Entity\Triprequest;
use App\Entity\Trips;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Triprequest1Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('people_number')
            ->add('active')
            ->add('processed')
            ->add('trip', EntityType::class, [
                'class' => Trips::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Triprequest::class,
        ]);
    }
}
