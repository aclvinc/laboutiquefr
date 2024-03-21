<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'), 
            SlugField::new('slug')
                ->setTargetFieldName('name'),
            ImageField::new('illustration')
                ->setBasePath('assets/images') // affichage de l'image dans easyadmin, chemin direct à partir de public
                ->setUploadDir('public/assets/images') // où copier l'image nécessite le chemin complet public/assets/images
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            MoneyField::new('price')
                ->setCurrency('EUR'),
            AssociationField::new('category'), // overrider __tostring de Category.php
        ];
        /*
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),*/    
    }
    
}
