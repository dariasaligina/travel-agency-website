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
            ->add('name', TextType::class, [ // Указываем тип поля явно
                'label' => 'Ваше имя', // Изменяем название поля "name"
            ])
            ->add('email', EmailType::class, [ // Указываем тип поля явно
                'label' => 'Ваш Email', // Изменяем название поля "email"
            ])
            ->add('phone', TextType::class, [ // Указываем тип поля явно
                'label' => 'Ваш телефон', // Изменяем название поля "phone"
            ])
            ->add('people_number', IntegerType::class, [ // Указываем тип поля явно (IntegerType или NumberType)
                'label' => 'Количество человек', // Изменяем название поля "people_number"
            ])
            ->add('trip', EntityType::class, [
                'class' => Trips::class,
                'choice_label' => function (Trips $trip): string {
                    // Форматируем дату в нужный вам формат
                    // Например, 'Y-m-d' для года-месяца-дня
                    // Или 'd.m.Y H:i' для дня.месяца.года Час:Минуты
                    return $trip->getStartDate()->format('d.m.Y');
                }, // Оставляем id как значение, но можем изменить отображение
                'label' => 'Выберите дату поездки', // Изменяем название поля "trip"
                'query_builder' => function (EntityRepository $er) use ($route, $now) { // Use $now here
                    return $er->createQueryBuilder('t')
                        ->where('t.route = :route')
                        ->andWhere('t.startDate > :now') // Use t.startDate and a parameter :now
                        ->setParameter('route', $route)
                        ->setParameter('now', $now) // Set the parameter :now to the current date/time
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
        // Specify the allowed types for the 'route' option
        $resolver->setAllowedTypes('route', [RouteEntity::class, 'null']);

    }
}
