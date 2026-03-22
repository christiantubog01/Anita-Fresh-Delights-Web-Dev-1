<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;

        $passwordOptions = [
            'mapped' => false,
            'required' => !$isEdit,
            'label' => $isEdit
                ? 'New Password (leave empty to keep current)'
                : 'Password',
        ];

        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Username cannot be blank']),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'mapped' => false,
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Staff' => 'ROLE_STAFF',
                    'User' => 'ROLE_USER',
                ],
                'placeholder' => 'Select a role',
                'required' => true,
                'data' => $isEdit && isset($options['user_roles'][0]) ? $options['user_roles'][0] : null,
            ])
            ->add('password', PasswordType::class, $passwordOptions)
            ->add('first_name', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'First name cannot be blank']),
                ],
            ])
            ->add('last_name', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Last name cannot be blank']),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => !$isEdit, // optional if editing
                'constraints' => !$isEdit ? [new NotBlank(['message' => 'Please enter an email'])] : [],
            ])
            ->add('birth_date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('isVerified', ChoiceType::class, [
                'label' => 'Verified?',
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
            'user_roles' => [], // current roles to preselect dropdown
        ]);
    }
}