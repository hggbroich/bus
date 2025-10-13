<?php

namespace App\Controller\Admin;

use App\Entity\StudentSibling;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StudentSiblingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StudentSibling::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
            AssociationField::new('studentAtSchool')
                ->setLabel('Schüler an der Schule')
                ->setRequired(false)
                ->autocomplete(),

            TextField::new('firstname', 'Vorname'),
            TextField::new('lastname', 'Nachname'),
            DateField::new('birthday', 'Geburtstag'),

            AssociationField::new('school')
                ->setLabel('Schule')
                ->autocomplete(),
        ];
    }
}
