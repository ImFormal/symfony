<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Prénom'
            ,
                'attr' =>[
                    'placeholder' => 'saisir le prénom'   
                ]
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Nom'
            ,
                'attr' =>[
                    'placeholder' => 'saisir le nom'   
                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email'
            ,
                'attr' =>[
                    'placeholder' => "saisir l'email"   
                ]
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Password'
            ,
                'attr' =>[
                    'placeholder' => "saisir le mot de passe"   
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}