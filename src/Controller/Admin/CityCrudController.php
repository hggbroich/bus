<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Import\Cities\CitiesImporter;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CityCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator
    ) { }

    public static function getEntityFqcn(): string
    {
        return City::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Stadt')
            ->setEntityLabelInPlural('Städte');
    }

    public function configureActions(Actions $actions): Actions {
        $importAction = Action::new('import', 'Importieren', 'fas fa-download')
            ->linkToCrudAction('import')
            ->createAsGlobalAction();

        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::BATCH_DELETE)
            ->add(Crud::PAGE_INDEX, $importAction);
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('federalState');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('plz', 'PLZ'),
            TextField::new('name', 'Name'),
            TextField::new('federalState', 'Bundesland'),
        ];
    }

    #[AdminRoute]
    public function import(CitiesImporter $citiesImporter): RedirectResponse {
        $citiesImporter->import();
        $this->addFlash('success', 'Städte importiert bzw. aktualisiert.');

        $url = $this->adminUrlGenerator
            ->setController(CityCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}
