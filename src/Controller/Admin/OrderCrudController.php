<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\StudentSiblingType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ORDER_ADMIN')]
class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Bestellung')
            ->setEntityLabelInPlural('Bestellungen');
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('student')
            ->add('ticket');
    }

    public function configureFields(string $pageName): iterable {
        return [
            FormField::addColumn(6),
            AssociationField::new('student', 'Schüler/Schülerin'),
            FormField::addColumn(6),
            AssociationField::new('country', 'Land'),

            FormField::addColumn(6),
            TextField::new('phoneNumber', 'Telefonnummer'),
            FormField::addColumn(6),
            EmailField::new('email', 'E-Mail-Adresse'),
            FormField::addColumn(6),
            TextField::new('iban', 'IBAN'),
            FormField::addColumn(6),
            AssociationField::new('ticket', 'Ticket'),

            FormField::addColumn(12),
            CollectionField::new('siblings', 'Geschwister')
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex()
                ->useEntryCrudForm()
                ->setFormTypeOption('by_reference', false)
        ];
    }
}
