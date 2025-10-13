<?php

namespace App\Controller\Admin;

use App\Entity\School;
use App\Import\Schools\SvwsGitHubSchoolsImporter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class SchoolCrudController extends AbstractCrudController
{

    public function __construct(private readonly AdminUrlGenerator $urlGenerator) {

    }

    public static function getEntityFqcn(): string
    {
        return School::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Schule')
            ->setEntityLabelInPlural('Schulen');
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('schoolNumber')
            ->add('name')
            ->add('city')
            ->add('plz');
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
            IntegerField::new('schoolNumber')
                ->setLabel('Schulnummer'),
            TextField::new('name'),
            TextField::new('address')
                ->setLabel('Adresse')
                ->setRequired(false),
            IntegerField::new('plz')
                ->setLabel('PLZ')
                ->setRequired(false),
            TextField::new('city')
                ->setLabel('Stadt')
                ->setRequired(false),
        ];
    }

    public function import(AdminContext $context, SvwsGitHubSchoolsImporter $importer): RedirectResponse {
        $result = $importer->import();
        $this->addFlash('success', sprintf('%d Schulen neu importiert und %d Schulen aktualisiert.', $result->added, $result->updated));

        $url = $this->urlGenerator->setController(SchoolCrudController::class)->setAction(Action::INDEX)->generateUrl();
        return $this->redirect($url);
    }
}
