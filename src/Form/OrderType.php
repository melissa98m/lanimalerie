<?php

namespace App\Form;
use App\Entity\Adress ;
use App\Entity\Carrier ;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
        ->add('adresses' , EntityType::class , [
            'label' => 'Choississez votre adresse de livraison' ,
            'required' => true ,
            'class' => Adress::class ,
            'choices' => $user->getAdresses(),
            'multiple' => false ,
            'expanded' => true ,
        ])
        ->add('carriers' , EntityType::class , [
            'label' => 'Choississez votre transporteur' ,
            'required' => true ,
            'class' => Carrier::class ,
            'multiple' => false ,
            'expanded' => true ,
        ])
        ->add('paiement' , ChoiceType::class , [
            'label' => 'Choississez votre moyen de paiement' ,
            'required' => true ,
            'multiple' => false ,
            'expanded' => true ,
            
            'choices' => [ 'Carte bleu' => 0,
                'Paypal' => 1]
        
        ])
        ->add('submit', SubmitType::class ,[
            'label' => 'Valider ma commande',
            'attr' => [
                'class' => 'btn btn-success btn-block',
            ]
        ] 
        )
    ;
}

public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults([

        
        // Configure your form options here
        'user'=> array()
    ]);
}
}