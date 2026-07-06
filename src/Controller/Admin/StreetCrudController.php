<?php

namespace App\Controller\Admin;

use App\Entity\Street;
use App\Import\Cities\StreetsImporter;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use React\Stream\ReadableResourceStream;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StreetCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator
    ) { }

    public static function getEntityFqcn(): string
    {
        return Street::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Straße')
            ->setEntityLabelInPlural('Straßen');
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
            ->add('city');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Name'),
            AssociationField::new('city', 'Stadt')
        ];
    }

    #[AdminRoute]
    public function import(StreetsImporter $importer): RedirectResponse {
        $importer->importAll();
        $this->addFlash('success', 'Straßen importiert bzw. aktualisiert.');

        $url = $this->adminUrlGenerator
            ->setController(StreetCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

}
