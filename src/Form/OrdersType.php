<?php

namespace App\Form;

use App\Entity\Adress ;
use App\Entity\Carrier ;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder
            
            ->add('dateCreate')
            ->add('carrierName')
            ->add('carrierPrice')
            ->add('delivery')
            ->add('state')
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
           
            'data_class' => Order::class,
        ]);
    }
}