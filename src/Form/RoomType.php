<?php

namespace App\Form;

use App\Entity\Equipment;
use App\Entity\Room;
use App\Repository\EquipmentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price' , null, [
                'label' => 'Cost per night'
            ])
        ->add('equipment', EntityType::class, [
            'class'=>Equipment::class,
            'query_builder' => function (EquipmentRepository $equipmentRepository) {
                return $equipmentRepository->createQueryBuilder('equipment')
                    ->orderBy('equipment.name', 'ASC');
            },
            'choice_label' => 'name',
            'expanded'=>true,
            'multiple'=>true,
            'label'=>'Available equipement'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
