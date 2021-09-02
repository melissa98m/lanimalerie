<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('name' , TextType::class)
        ->add('Firstname' , TextType::class)
        ->add('username', TextType::class)
        ->add('email')
        ->add('plainPassword', PasswordType::class, [
                'mapped' => false ,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ])
            ]
        ] )
        ->add('ConfirmPassword', PasswordType::class, [
            'mapped' => false ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => "Accepter les conditions d'utilisation",
                ]),
            ],
        ]);
    }

public function configureOptions(OptionsResolver $resolver){
    $resolver->setDefaults([
        'data_class' => User::class,
    ]);
}
}