<?php

namespace App\Form;

use App\Tools\Quantity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QuantityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => false,
                'required' => false,
                'invalid_message' => "La valeur {{ value }} n'est pas une quantité valide.",
                'attr' => [
                    'class' => "form-control", 
                    'style' => "max-width: 80px;",
                    'value' => 1,
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter au panier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quantity::class,
            'method' => 'GET', 
            'csrf_protection' => false,
        ]);
    }
/*
    public function getBlockPrefix()
    {
        return '';
    }*/
}

