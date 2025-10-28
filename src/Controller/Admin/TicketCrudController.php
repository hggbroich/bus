<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use App\Form\TicketPaymentIntervallType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class TicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ticket::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Ticket')
            ->setEntityLabelInPlural('Tickets');
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('name')
            ->add('description');
    }

    public function configureFields(string $pageName): iterable {
        return [
            TextField::new('name'),
            TextField::new('description')
                ->setLabel('Beschreibung'),
            IntegerField::new('priority')
                ->setLabel('Priorität')
                ->setHelp('Diese Priorität wird beim automatischen Zuweisen des Tickets verwendet. Die entsprechende Zuweisungsstrategie kann in den Bestellungs-Einstellungen festgelegt werden.'),
            TextField::new('defaultExternalId')
                ->setLabel('Externe ID (ohne Zahlungsintervall)')
                ->setHelp('Diese ID wird exportiert, sofern bei einem Kind noch kein Zahlungsintervall hinterlegt ist.'),
            CollectionField::new('paymentIntervals', 'Zahlungsintervalle')
                ->allowAdd()
                ->allowDelete()
                ->hideOnIndex()
                ->setEntryType(TicketPaymentIntervallType::class)
                ->setFormTypeOption('by_reference', false)
        ];
    }
}
