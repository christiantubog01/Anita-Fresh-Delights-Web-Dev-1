<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\NumberType; 

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product_name')
            ->add('product_description')
            ->add('image', FileType::class, [
                    'label' => 'Product Image (JPEG or PNG file)',
                    'mapped' => false, // not directly linked to entity field
                    'required' => $options['is_create'], // required only on create, change it to false or true to change back
                    'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image',
                ]),
                    ],
                    ])
            ->add('price', NumberType::class, [
                'scale' => 2,        // keep two decimals
                'html5' => true,     // renders <input type="number">
                'required' => true,
                'attr' => [
                    'min' => '0',
                    'step' => '0.01',
                    'inputmode' => 'decimal',
                    'pattern' => '\d+(\.\d{1,2})?' 
                ],
            ])
            // ->add('date_created')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
            ])
            ->add('stock', EntityType::class, [
                'class' => Stock::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'is_create' => false, // default is edit mode remove this to go back
        ]);
    }
}
