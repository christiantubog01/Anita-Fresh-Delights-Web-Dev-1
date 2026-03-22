<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EmailType; 

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            // New fields
            ->add('first_name', TextType::class, [
                'constraints' => [
                new Assert\NotBlank([
                'message' => 'First name cannot be blank.',
            ]),
                new Assert\Regex([
                'pattern' => '/^[A-Za-z ]+$/',
                'message' => 'First name can only contain letters and spaces.',
            ]),
                ],
            ])

            ->add('last_name', TextType::class, [
                'constraints' => [
                new Assert\NotBlank([
                'message' => 'Last name cannot be blank.',
            ]),
                new Assert\Regex([
            'pattern' => '/^[A-Za-z ]+$/',
            'message' => 'Last name can only contain letters and spaces.',
            ]),
                ],
            ])
            ->add('username', TextType::class, [
    'constraints' => [
        new Assert\NotBlank([
            'message' => 'Username cannot be blank.',
        ]),
        new Assert\Length([
            'min' => 6,
            'minMessage' => 'Username must be at least {{ limit }} characters long.',
        ]),
        new Assert\Regex([
            'pattern' => '/^[A-Za-z0-9]+$/',
            'message' => 'Username can only contain letters and numbers (no spaces or special characters).',
        ]),
    ],
])
            ->add('birth_date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'attr' => ['autocomplete' => 'email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an email',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'Admin' => 'ROLE_ADMIN',
            //         'Staff' => 'ROLE_STAFF',
            //         'User' => 'ROLE_USER',
            //     ],
            //     'multiple' => true,
            //     'expanded' => true,
            //     'label' => 'Roles',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
