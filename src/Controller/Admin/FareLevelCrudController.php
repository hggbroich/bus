<?php

namespace App\Controller\Admin;

use App\Entity\FareLevel;
use App\FareLevel\FareLevelHelper;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class FareLevelCrudController extends AbstractCrudController
{

    public function __construct(private readonly AdminUrlGenerator $urlGenerator) {

    }

    public static function getEntityFqcn(): string {
        return FareLevel::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Preisstufe')
            ->setEntityLabelInPlural('Preisstufen');
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('name')
            ->add('city')
            ->add('level');
    }

    public function configureActions(Actions $actions): Actions {
        $addMissingAction = Action::new('addMissing', 'Fehlende Preisstufen hinzufügen', 'fa fa-sync')
            ->linkToCrudAction('addMissing')
            ->createAsGlobalAction();

        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $addMissingAction);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Name')
                ->setHelp('Nur ein interner Anzeigename'),
            TextField::new('city', 'Stadt')
                ->setHelp('Anhand der Stadt (des Kindes) wird die Preisstufe bestimmt.'),
            TextField::new('level', 'Preisstufe')
                ->setHelp('Dieser Wert wird später exportiert.'),
        ];
    }

    public function addMissing(AdminContext $context, FareLevelHelper $fareLevelHelper): RedirectResponse {
        $count = $fareLevelHelper->addMissingLevels();

        $this->addFlash('success', sprintf('%d Preisstufen hinzufügt (mit Preisstufe 0).', $count));

        $url = $this->urlGenerator->setController(FareLevelCrudController::class)->setAction(Action::INDEX)->generateUrl();
        return $this->redirect($url);
    }
}
