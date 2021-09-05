<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email' , EmailType::class , [
                'disabled' => true,
                'label' => 'Mon email',
            ])
            ->add('name', TextType::class, [
                'disabled' => true ,
                'label' => 'Mon nom'
            ])
            ->add('firstname' , TextType::class, [
                'disabled' => true ,
                'label' => 'Mon premon'
            ])
            ->add('plainPassword' , PasswordType::class , [
                'label' => 'Mon mot de passe actuel' ,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Saisir le mot de passe actuel'
                ]
            ])
            ->add('newpassword', RepeatedType::class, [
                'type'=> PasswordType::class,
                'invalid_message' => 'le mot de passe et la confirmation doivent correspondre',
                'label' => 'Mon nouveau mot de passe',
                'required' => true,
                'mapped' => false ,
                'first_options' => [
                    'label'=> 'Mon nouveau mot de passe' ,
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre nouveau mot de passe'
                    ]
                    ],
                'second_options' => [
                    'label' => 'confirmer votre nouveau mot de passe' , 
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre nouveau mot de passe'
                    ]
                ]
            ])
            ->add('submit' , SubmitType::class , [
                'label' => 'Modifier mot de passe'
            ])
            ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
