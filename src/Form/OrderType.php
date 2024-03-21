<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Carrier;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        //dd($options['user']->getId());
        $user = $options['user'];
        $builder
            ->add('adresses', EntityType::class, [
                'class' => Adress::class,
                'choices' => $user->getAdresses(),
                'multiple' => false,
                'expanded' => true,
                'choice_attr' => function ($choice, string $key, mixed $value) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'mt-0'];
                },
            ])
            ->add('carriers', EntityType::class, [
                'class' => Carrier::class,
                'multiple' => false,
                'expanded' => true,
                'choice_attr' => function ($choice, string $key, mixed $value) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'mt-0'];
                },
            ])    
            ->add("submit", SubmitType::class, [
                'label' => "Valider la commande",
                'attr' => [
                    'class' => 'btn btn-info btn-sm btn-block',
                ]
            ])        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => [],
        ]);
    }
}
