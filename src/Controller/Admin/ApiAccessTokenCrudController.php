<?php

namespace App\Controller\Admin;

use App\Entity\ApiAccessToken;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ApiAccessTokenCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string {
        return ApiAccessToken::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('API-Token')
            ->setEntityLabelInPlural('API-Tokens');
    }

    public function configureActions(Actions $actions): Actions {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable {
        return [
            AssociationField::new('user', 'Benutzer'),
            TextField::new('description', 'Beschreibung'),
            TextField::new('accessToken', 'Access-Token')
                ->setDisabled()
                ->hideOnIndex()
        ];
    }
}
