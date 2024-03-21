<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Intitulé de l'adresse"
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Votre prénom"
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Votre nom"
                ]
            ])
            ->add('company', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Nom de votre société (facultatif)."
                ]
            ])
            ->add('adress', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Votre adresse"
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Le code postal"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "La Ville"
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Votre pays"
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Numéro de téléphone"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Ajouter l'adresse",
                'attr' => [
                    'class' => 'btn btn-success btn-lg btn-block mt-5',
                ]
            ])
            ->add('indic', ChoiceType::class, [
                'choices'  => [
                    '(+689)' => '689',
                    '(+33)' => '33',
                    '(+590)' => '590',
                ],
                'attr' => [
                    'style' => "max-width: 120px;",
                ]
            ])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
