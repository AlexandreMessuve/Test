<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name')->setLabel('Nom'),
            TextareaField::new('step_description')->setLabel('Description'),
            IntegerField::new('minute')->setLabel('Minute'),
            MoneyField::new('price')->setLabel('Prix')->setCurrency('EUR'),
            IntegerField::new('numberOfPer')->setLabel('Nombre de personne'),
            IntegerField::new('difficulty')->setLabel('Difficulté 1 a 5'),
            BooleanField::new('isFavorite')->setLabel('Favoris'),
            DateField::new('createAt')->setLabel('Date de création')->onlyOnIndex(),
            DateField::new('updateAt')->setLabel('Mis a jour le')->onlyOnIndex(),
        ];
    }

}
