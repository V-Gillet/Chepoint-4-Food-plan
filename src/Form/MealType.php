<?php

namespace App\Form;

use App\Entity\Meal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('origin')
            ->add('lipid')
            ->add('carb')
            ->add('protein')
            ->add('calories')
            ->add(
                'type',
                ChoiceType::class,
                []
            )
            ->add('isRecipe')
            ->add('date');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meal::class,
        ]);
    }
}