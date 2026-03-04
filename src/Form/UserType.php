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
        $isEdit = $options['is_edit'];

        $passwordOptions = [
            'mapped' => false,
            'required' => !$isEdit,
            'label' => $isEdit
                ? 'New Password (leave empty to keep current)'
                : 'Password',
        ];

        $builder
            ->add('username')

            // ✅ ROLE DROPDOWN (single role only)
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'mapped' => false,
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Staff' => 'ROLE_STAFF',
                    'User'  => 'ROLE_USER',
                ],
                'placeholder' => 'Select a role',
                'required' => true,
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
            'is_edit' => false,
        ]);
    }
}