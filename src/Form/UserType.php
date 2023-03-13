<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => '50'
                ],
                'label' => 'Username*',
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
                'label' => 'Prenom*',
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
                'label' => 'Nom*',
                'label_attr' => ['class' => 'label-control'],
                'constraints' => [
                    new Assert\Length(['max' => 50]),
                    new Assert\NotBlank(),
                ]
            ])
            ->add('dateNaissance', DateType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date de naissance*',
                'label_attr' => ['class' => 'label-control'],
            ])
            ->add('bio', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre biographie :'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Votre biographie'
            ])
            ->add('plainPassword', PasswordType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => '*********'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Mot de passe*'
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
