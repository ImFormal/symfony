<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextField::new('content', 'Contenu'),
            DateField::new('createAt', 'Créé le :'),
            AssociationField::new('author', 'Auteur')->hideOnIndex(),
            AssociationField::new('categories', 'Catégories')->hideOnIndex()
        ];  
    }
}