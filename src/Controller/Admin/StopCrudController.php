<?php

namespace App\Controller\Admin;

use App\Entity\Stop;
use App\Import\Stops\GtfsStopsImporter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class StopCrudController extends AbstractCrudController
{
    public function __construct(private readonly AdminUrlGenerator $urlGenerator) {

    }

    public static function getEntityFqcn(): string
    {
        return Stop::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Haltestelle')
            ->setEntityLabelInPlural('Haltestellen');
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('externalId')
            ->add('name')
            ->add('latitude')
            ->add('longitude');
    }

    public function configureActions(Actions $actions): Actions {
        $importAction = Action::new('import', 'Importieren', 'fas fa-download')
            ->linkToCrudAction('import')
            ->createAsGlobalAction();

        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::BATCH_DELETE)
            ->add(Crud::PAGE_INDEX, $importAction);
    }

    public function configureFields(string $pageName): iterable {
        return [
            TextField::new('name'),
            NumberField::new('latitude')
                ->setLabel('Breitengrad')
                ->setNumDecimals(6),
            NumberField::new('longitude')
                ->setLabel('Längengrad')
                ->setNumDecimals(6),
            TextField::new('externalId')
                ->setLabel('Externe ID')
                ->hideOnIndex(),
        ];
    }

    public function import(AdminContext $context, GtfsStopsImporter $importer): RedirectResponse {
        $result = $importer->import();
        $this->addFlash('success', sprintf('%d Haltestellen neu importiert und %d Haltestellen aktualisiert.', $result->added, $result->updated));

        $url = $this->urlGenerator->setController(StopCrudController::class)->setAction(Action::INDEX)->generateUrl();
        return $this->redirect($url);
    }
}
