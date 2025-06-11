<?php

namespace App\Form;

use App\Entity\Triprequest;
use App\Entity\Trips;
use App\Entity\Route as RouteEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Import specific field types if needed
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Doctrine\ORM\EntityRepository;
use \DateTimeImmutable;

class TriprequestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $route = $options['route'];
        $now = new DateTimeImmutable();

        $builder
            ->add('name', TextType::class, [ 
                'label' => 'Ваше имя', 
            ])
            ->add('email', EmailType::class, [ 
                'label' => 'Ваш Email', 
            ])
            ->add('phone', TextType::class, [ 
                'label' => 'Ваш телефон',
            ])
            ->add('people_number', IntegerType::class, [ 
                'label' => 'Количество человек', 
            ])
            ->add('trip', EntityType::class, [
                'class' => Trips::class,
                'choice_label' => function (Trips $trip): string {
                    return $trip->getStartDate()->format('d.m.Y');
                }, 
                'label' => 'Выберите дату поездки', 
                'query_builder' => function (EntityRepository $er) use ($route, $now) { 
                    return $er->createQueryBuilder('t')
                        ->where('t.route = :route')
                        ->andWhere('t.startDate > :now')
                        ->setParameter('route', $route)
                        ->setParameter('now', $now)
                        ->orderBy('t.startDate', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Triprequest::class,
        ]);
        $resolver->setRequired('route');
        $resolver->setAllowedTypes('route', [RouteEntity::class, 'null']);

    }
}
