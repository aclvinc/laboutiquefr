<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "label" => "Identifiant unique",
                "attr" => [
                    'placeholder' => "Votre adresse mail",
                ],
                "constraints" => [
                    new Length([
                        'min' => 10, 'max' => 60, 
                        'minMessage' => "Le mail doit faire au minimum 10 caractères",
                        'maxMessage' => "Le mail ne doit pas dépasser les 60 caractères",
                        ])
                    ],
            ])
            ->add("password", RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et sa confirmation doivent être identiques.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Votre mot de passe',
                    'attr' => ['placeholder' => 'Saisir un mot de passe'],
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => ['placeholder' => 'Saisir votre mot de passe de nouveau'],
                ],                
            ])
            ->add('firstname', TextType::class, [
                    "label" => "Votre prénom",
                    "constraints" => [
                        new Length([
                            'min' => 2, 'max' => 200, 
                            'minMessage' => "Le texte doit faire au minimum 2 caractères",
                            'maxMessage' => "Le texte ne doit pas dépasser les 200 caractères",
                        ])
                    ],
                    'attr' => [
                        'placeholder' => "Votre prénom"
                    ],                    
                ],

            )
            ->add('lastname', TextType::class, [
                "label" => "Votre nom",
                "attr" => [
                    'placeholder' => "Votre nom",
                ],
                "constraints" => [
                    new Length([
                        'min' => 2, 'max' => 200, 
                        'minMessage' => "Le texte doit faire au minimum 2 caractères",
                        'maxMessage' => "Le texte ne doit pas dépasser les 200 caractères",
                    ])
                ],
            ])
            ->add("submit", SubmitType::class, [
                "label" => "S'enregistrer",
                'attr' => [
                    'class' => "btn btn-lg btn-block btn-info mt-2"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

