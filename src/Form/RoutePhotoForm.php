<?php

namespace App\Form;

use App\Entity\Route as RouteEntity;
use App\Entity\RoutePhoto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RoutePhotoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, [ 
                    'label' => 'Photo (Image file)',
                    'mapped' => false, 
                    'required' => false, 
                ])
            ->add('route', EntityType::class, [
                'class' => RouteEntity::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RoutePhoto::class,
        ]);
    }
}
