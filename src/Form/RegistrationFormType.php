<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class , [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'email',
                ],
                'label' => 'Email',
                'label_attr' => ['class' => 'label-control'],
                'constraints' => [
                    new Assert\NotBlank(),

                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                    'type' => 'checkbox'
                ],
                'label' => 'Veuillez accepter les termes',
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'mapped' => false,
                'constraints' => [
                    new Assert\IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller

                'hash_property_path' => 'password',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Password',
                'label_attr' => [
                    'class' => 'label-control'
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'max' => '50'
                ],
                'label' => 'Username',
                'label_attr' => [
                    'class' => 'label-control'
                ],
                'constraints' => [
                    new Assert\Length(['max' => 50]),
                    new Assert\NotBlank(),
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'max' => '50'
                ],
                'label' => 'Prenom',
                'label_attr' => ['class' => 'label-control'],
                'constraints' => [
                    new Assert\Length(['max' => 50]),
                    new Assert\NotBlank(),
                ]
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'max' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => ['class' => 'label-control'],
                'constraints' => [
                    new Assert\Length(['max' => 50]),
                    new Assert\NotBlank(),
                ]
            ])
            ->add('dateNaissance', DateType::class, [
                'attr' => [
                    'class' => 'js-datepicker form-control',
                ],
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'Date de naissance',
                'label_attr' => ['class' => 'label-control'],
            ])


            ->add('submit', SubmitType::class , [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
