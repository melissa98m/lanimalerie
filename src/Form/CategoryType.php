<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name' , TextType::class)
        ->add('price' , TextType::class)
        ->add('EnStock' , ChoiceType::class , ['choices' =>['True' => 0,'false' => 1,]])
        ->add('reference' , IntegerType::class)
        ->add('imageProduct' , FileType::class , [
            'label' => 'imageProduct' , 
            'mapped' => false , // permet quil ne soit pas traiter directement
            'required' => false , 
            'constraints' => [  // ajoute de contrainte
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [ // types de fichiers que l'on veux recuperer
                        'image/png',
                        'image/jpg',
                        'image/jpeg',
                        'image/webp',
                    ],
                    'mimeTypesMessage' => 'Merci de charger un format valide' ,
                ])
            ]
            ])
        ->add('description' , TextType::class)
        ->add('brand' , EntityType::class , array('class' => Brand::class, 
                                                'choice_label' => 'name'))
        ->add('category' , EntityType::class , array('class' => Category::class, 
                                            'choice_label' => 'name' , 
                                            'multiple' => true , 
                                            'expanded' => true))
       
                         
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
