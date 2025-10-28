<?php

namespace App\Controller\Admin;

use App\Entity\PaymentInterval;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PaymentIntervalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string {
        return PaymentInterval::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Zahlungsintervall')
            ->setEntityLabelInPlural('Zahlungsintervalle');
    }

    public function configureFields(string $pageName): iterable {
        return [
            TextField::new('name')
        ];
    }
}
