<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Votre adresse mail'
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => 'Votre prénom'
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => 'Votre nom'
            ])
            ->add('old_password', PasswordType::class, [
                'mapped' => false,
                'label' => 'Votre ancien mot de passe',
                'attr' => [
                    'placeholder' => 'Saisir ici votre ancien mot de passe'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe doit correspondre à la vérification.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Nouveau mot de passe', 
                    'attr' => ['placeholder' => 'Saisir le nouveau mot de passe',]
                ],
                'second_options' => [
                    'label' => 'Vérification - Répéter votre mot de passe', 
                    'attr' => ['placeholder' => 'Resaisir le nouveau mot de passe',],
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier le mot de passe',
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
