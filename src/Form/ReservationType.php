<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checkIn', DateType::class, [
                'input'  => 'datetime_immutable',
                'widget' => 'single_text',
                'constraints' =>[
                    new GreaterThanOrEqual([
                        'value'=> new \DateTime('today')
                    ])
                ]
            ])
            ->add('checkOut', DateType::class, [
                'input'  => 'datetime_immutable',
                'widget' => 'single_text',
                'constraints' =>[
                    new GreaterThan([
                        'propertyPath' => 'parent.all[checkIn].data'
                    ]),
                ]
            ])
            ->add('numberOfPeople', ChoiceType::class, [
                'choices'  => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
