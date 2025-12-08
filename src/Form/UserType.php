<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit']; // Custom option

    $passwordOptions = [
        'mapped' => false,
        'required' => !$isEdit, // required only when NOT edit (new user)
        'label' => $isEdit
            ? 'New Password (leave empty to keep current)'
            : 'Password',
    ];


        $builder
            ->add('username')
            ->add('roles', ChoiceType::class, [
    'choices' => [
        'Admin' => 'ROLE_ADMIN',
        'Staff' => 'ROLE_STAFF',
        'User' => 'ROLE_USER',
    ],
    'multiple' => true,
    'expanded' => false, // checkboxes
])

            ->add('password', PasswordType::class, $passwordOptions)
            ->add('first_name')
            ->add('last_name')
            ->add('birth_date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false, // default value (for NEW)
        ]);
    }
}
