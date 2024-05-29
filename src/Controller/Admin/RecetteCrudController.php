<?php

namespace App\Controller\Admin;

use App\Entity\Recette;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class RecetteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recette::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('libelle')->setLabel('Nom'),
            AssociationField::new('difficulte'),
            AssociationField::new('categorie'),
            TimeField::new('tempsPreparation'),
            AssociationField::new('ingredients'),
            TextEditorField::new('description'),
            SlugField::new('slug')->setTargetFieldName('libelle'),
            ImageField::new('photo')->setLabel('Image')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/uploads')->setUploadDir('/public/uploads'),
            BooleanField::new('isPublic')->setLabel('Recette publique'),
            AssociationField::new('auteur')->setLabel('Le propriétaire de la recette'),
            AssociationField::new('province'),

        ];
    }
}
