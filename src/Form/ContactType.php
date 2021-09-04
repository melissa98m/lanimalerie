<?php
namespace App\Form;


use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder , array $options)
    {
        $builder
            
            ->add('civilite', ChoiceType::class, ['choices'  => ['M' => 'M','Mme' => 'Mme'],'expanded' => true,])                                           
            ->add('nom', TextType::class)
            ->add('prenom' , TextType::class)
            ->add('email', EmailType::class)
            ->add('motif' , ChoiceType::class , [ 'choices' => ['Demande de renseignements' => 'Demande de renseignements' ,
                                                                'Demande de devis pour service 3.0' => 'Demande de devis service 3.0' ,
                                                                'Recrutement ' => 'Recrutement'],
                                                                'required' => true ,
                                                                'mapped' => false,
                                                                'multiple' => false
                                                                    ]
                                                                )
            ->add('message', TextareaType::class, ['attr'=> ['rows'=> 5]])
            ->add('envoyer', SubmitType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}