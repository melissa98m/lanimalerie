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

class SavType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder , array $options)
    {
        $builder
            ->add('Numero' , TextType::class)                                        
            ->add('email', EmailType::class)
            ->add('Motif' , ChoiceType::class ,['choices'  => [
                                                            'Produit defectueux' => 'Produit defectueux',
                                                            'Produit incomplet' => 'Produit incomplet',
                                                            'Produit casser' =>'Produit casser',
                                                            'Produit perimer' => 'Produit perimer'
                                                            ], 'expanded' => false,
                                                            'multiple' => false
                                                            ] )
            ->add('message', TextareaType::class, ['attr'=> ['rows'=> 6]])   
            ->add('valider', SubmitType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}