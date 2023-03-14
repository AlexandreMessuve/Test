<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email')->setLabel('Email'),
            TextField::new('username')->setLabel('Pseudo'),
            TextField::new('plainPassword')->setLabel('Password')->setFormType(PasswordType::class)->onlyWhenCreating(),
            TextField::new('nom')->setLabel('Nom'),
            TextField::new('prenom')->setLabel('Prénom'),
            DateField::new('dateNaissance')->setLabel('Date de naissance')->setFormat('dd MMM Y')->setTimezone('Europe/Paris'),
            ArrayField::new('roles')->setLabel('Role')->hideWhenCreating(),
            DateField::new('createdAt')->setLabel('Date de création')->onlyOnIndex()->setFormat('dd MMM Y HH:mm')->setTimezone('Europe/Paris'),
        ];
    }

}
