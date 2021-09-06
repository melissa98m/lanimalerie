<?php

namespace App\Form;

use App\Entity\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('logo' , FileType::class , [
                'label' => 'Logo' , 
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
        ]);
    }
}