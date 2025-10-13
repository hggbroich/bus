<?php

namespace App\Controller\Admin;

use App\Entity\Country;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class CountryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Country::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Land')
            ->setEntityLabelInPlural('Länder');
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('name')
            ->add('isoCode');
    }

    public function configureFields(string $pageName): iterable {
        return [
            TextField::new('name'),
            TextField::new('isoCode')
                ->setLabel('ISO 3166-1 alpha-2 Code')
                ->setHelp('ISO 3166-1 alpha-2 Code in Großbuchstaben')
        ];
    }
}
