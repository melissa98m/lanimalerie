<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstname' , TextType::class , ['label'
                  => "Votre Prenom" , 
                  'attr' => ['placeholder' => ' Votre prénom' ]
                  ])
        ->add('lastname' , TextType::class ,  ['label'
                 => "Votre nom" , 
                 'attr' => ['placeholder' => ' Votre Nom' ]
                 ])
        ->add('company', TextType::class , ['label'
              => "Votre company" , 
              'required' => false ,
                 'attr' => ['placeholder' => 'Nom de la companie (optionnel)' ]
                 ])
        ->add('numero', IntegerType::class , ['label'
                => "Numero" , 
                'attr' => ['placeholder' => 'Numero de la rue'] 
                ])
        ->add('name', TextType::class , ['label'
                 => "Nom de la rue" , 
                 'attr' => ['placeholder' => 'Nom de la rue']]
                 )
        ->add('codepostal', IntegerType::class ,['label' => "Code Postal" , 
             'attr' => ['placeholder' =>'Votre code postal']
             ])
        ->add('ville' , TextType::class ,['label'=> "Ville" , 
             'attr' => ['placeholder' => ' Votre ville' ]
             ])
        ->add('pays' , CountryType::class , ['label'=> "Pays" , 
          'attr' => ['placeholder' => 'Pays' ]])

        ->add('Submit' , SubmitType::class , [
            'label' => "Valider l'adresse" ,
            'attr' => ['class' => 'btn-block btn-info']
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
