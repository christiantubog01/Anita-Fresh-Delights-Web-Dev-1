<?php

namespace App\Form;

use App\Entity\Stock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\NumberType; 

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, [
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
            ->add('stock_description')
            ->add('min_quantity', NumberType::class, [
                'scale' => 2,        
                'html5' => true,     
                'required' => true,
                'attr' => [
                    'min' => '0',
                    'step' => '0.01',
                    'inputmode' => 'decimal',
                    'pattern' => '\d+(\.\d{1,2})?' 
                ],
            ])
            ->add('max_quantity', NumberType::class, [
                'scale' => 2,       
                'html5' => true,     
                'required' => true,
                'attr' => [
                    'min' => '0',
                    'step' => '0.01',
                    'inputmode' => 'decimal',
                    'pattern' => '\d+(\.\d{1,2})?' 
                ],
            ])
            ->add('unit')
            // ->add('date_created')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
