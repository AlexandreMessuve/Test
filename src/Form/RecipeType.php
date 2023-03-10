<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RecipeType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security){
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank
                ]

            ])
            ->add('minute', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1440,
                ],
                'label' => 'Temps (en minutes)',
                'label_attr' => [
                    'class' => 'label-control mt-4',
                ],
                'constraints' => [
                    new  Assert\Positive(),
                    new Assert\LessThan(1441)
                ]
            ])
            ->add('numberOfPer', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 50,
                ],
                'label' => 'Nombre de personnes',
                'label_attr' => [
                    'class' => 'label-control mt-4',
                ],
                'constraints' => [
                    new  Assert\Positive(),
                    new Assert\LessThan(51)
                ]
            ])
            ->add('difficulty', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 5,
                ],
                'label' => 'Difficulté',
                'label_attr' => [
                    'class' => 'label-control mt-4',
                ],
                'constraints' => [
                    new  Assert\Positive(),
                    new Assert\LessThan(6)
                ]
            ])
            ->add('step_description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'max' => 300,
                ],
                'label' => 'Etape / Description',
                'label_attr' => [
                    'class' => 'label-control mt-4',
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Prix ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1001)
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                    ],
                'required' => false,
                'label' => 'Ajouter au favoris',
                'label_attr' => ['class' => 'form-check-label'],
                'constraints' => [
                    new Assert\NotNull()
                ]
            ])
            ->add('ingredientsList', EntityType::class, [
                'attr' => [
                    'class' => 'mx-4'
                ],
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $r){
                    return $r->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user', $this->security->getUser());
                },
                'choice_label' => 'name',
                'label' => 'Les ingrédients',
                'label_attr' => ['class' => 'form-label mt-4'],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
