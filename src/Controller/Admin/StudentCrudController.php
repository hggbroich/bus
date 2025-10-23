<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Field\ButtonField;
use App\Entity\Student;
use App\Import\Students\CsvSchildImporter;
use App\Import\Students\ImportRequest;
use App\Import\Students\ImportRequestType;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_STUDENT_ADMIN')]
class StudentCrudController extends AbstractCrudController
{
    public function __construct(private readonly AdminUrlGenerator $urlGenerator) {

    }

    public static function getEntityFqcn(): string {
        return Student::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('Schüler(in)')
            ->setEntityLabelInPlural('Schüler');
    }

    public function configureActions(Actions $actions): Actions {
        $importAction = Action::new('import', 'Importieren', 'fa-solid fa-upload')
            ->linkToCrudAction('import')
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $importAction)
            ->disable(Action::NEW, Action::DELETE, Action::BATCH_DELETE);
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('firstname')
            ->add('lastname')
            ->add('externalId')
            ->add('grade')
            ->add('email')
            ->add('plz')
            ->add('city')
            ->add('sgb12')
            ->add('stop')
            ->add('publicSchool')
            ->add('distanceToPublicSchool')
            ->add('confirmedDistanceToPublicSchool')
            ->add('distanceToSchool')
            ->add('confirmedDistanceToSchool');
    }

    public function configureFields(string $pageName): iterable {
        return [
            FormField::addTab('Stammdaten'),
            FormField::addColumn(6),
            TextField::new('externalId')
                ->setLabel('Schild-ID')
                ->hideOnIndex()
                ->setDisabled(),
            FormField::addColumn(6),
            TextField::new('status')
                ->hideOnIndex()
                ->setDisabled(),
            FormField::addColumn(6),
            TextField::new('firstname')
                ->setLabel('Vorname')
                ->setDisabled(),
            FormField::addColumn(6),
            TextField::new('lastname')
                ->setLabel('Nachname')
                ->setDisabled(),
            FormField::addColumn(6),
            EmailField::new('email')
                ->setLabel('E-Mail')
                ->setRequired(false)
                ->setDisabled(),
            FormField::addColumn(6),
            TextField::new('grade')
                ->setLabel('Klasse')
                ->setRequired(false)
                ->setDisabled(),
            FormField::addColumn(6),
            TextField::new('street')
                ->setLabel('Straße')
                ->setRequired(false)
                ->hideOnIndex()
                ->setDisabled(),
            FormField::addColumn(6),
            TextField::new('houseNumber')
                ->setLabel('Hausnummer')
                ->setRequired(false)
                ->hideOnIndex()
                ->setDisabled(),
            FormField::addColumn(6),
            IntegerField::new('plz')
                ->setLabel('PLZ')
                ->setRequired(false)
                ->hideOnIndex()
                ->setDisabled(),
            FormField::addColumn(6),
            TextField::new('city')
                ->setLabel('Stadt')
                ->setRequired(false)
                ->hideOnIndex()
                ->setDisabled(),
            FormField::addColumn(6),
            DateField::new('entranceDate')
                ->setLabel('Eintrittsdatum')
                ->setDisabled()
                ->hideOnIndex(),
            FormField::addColumn(6),
            DateField::new('leaveDate')
                ->setLabel('Abgangsdatum')
                ->setDisabled()
                ->hideOnIndex(),
            FormField::addColumn(6),
            DateField::new('birthday')
                ->setLabel('Geburtstag')
                ->setDisabled()
                ->hideOnIndex(),
            FormField::addColumn(6),
            ChoiceField::new('gender')
                ->setLabel('Geschlecht')
                ->setDisabled()
                ->hideOnIndex(),

            FormField::addTab('Bus- und Haltestellendaten'),
            IntegerField::new('busCompanyCustomerId')
                ->setLabel('Kundennummer Busunternehmen')
                ->hideOnIndex(),
            BooleanField::new('sgb12')
                ->setLabel('SGB12')
                ->setRequired(false)
                ->hideOnIndex(),
            AssociationField::new('stop')
                ->setLabel('Haltestelle')
                ->autocomplete()
                ->setRequired(false)
                ->hideOnIndex(),
            FormField::addColumn(6),
            FormField::addPanel('Öffentliche Schule'),
            AssociationField::new('publicSchool')
                ->setLabel('Öffentliche Schule')
                ->autocomplete()
                ->setRequired(false)
                ->hideOnIndex(),
            NumberField::new('distanceToPublicSchool')
                ->setLabel('Distanz zur öffentlichen Schule (Eltern)')
                ->setRequired(false)
                ->setHelp('in km')
                ->hideOnIndex(),
            BooleanField::new('isDistanceToPublicSchoolConfirmed', 'Distanz zur öff. Schule bestätigt')
                ->setDisabled(true)
                ->setVirtual(true)
                ->hideOnDetail()
                ->hideOnForm(),
            NumberField::new('confirmedDistanceToPublicSchool')
                ->setLabel('Distanz zur öffentlichen Schule (geprüft)')
                ->setRequired(false)
                ->setHelp('in km')
                ->hideOnIndex(),
            /*ButtonField::new('foo', 'Label')
                ->onlyOnDetail()
                ->setUrl('https://www.google.com/maps/dir/?api=1&travelmode=walking&origin={street} {houseNumber}, {plz} {city}&destination={publicSchool.address}, {publicSchool.plz} {publicSchool.city}'),*/
            FormField::addColumn(6),
            FormField::addPanel('Eigene Schule'),
            NumberField::new('distanceToSchool')
                ->setLabel('Distanz zur Schule (Eltern)')
                ->setRequired(false)
                ->setHelp('in km')
                ->hideOnIndex(),
            BooleanField::new('isDistanceToSchoolConfirmed', 'Distanz zur Schule bestätigt')
                ->setDisabled(true)
                ->setVirtual(true)
                ->hideOnDetail()
                ->hideOnForm(),
            NumberField::new('confirmedDistanceToSchool')
                ->setLabel('Distanz zur Schule (geprüft)')
                ->setRequired(false)
                ->setHelp('in km')
                ->hideOnIndex(),
        ];
    }

    #[AdminRoute('/import', name: 'import_students')]
    public function import(CsvSchildImporter $importer, Request $request): Response {
        $importRequest = new ImportRequest();
        $form = $this->createForm(ImportRequestType::class, $importRequest);
        $form->handleRequest($request);

        $validationException = null;
        if($form->isSubmitted() && $form->isValid()) {
            try {
                $result = $importer->import($importRequest);
                $this->addFlash('success', sprintf('%d Schüler neu importiert und %d Schüler aktualisiert.', $result->added, $result->updated));

                $url = $this->urlGenerator->setController(StudentCrudController::class)->setAction(Action::INDEX)->generateUrl();
                return $this->redirect($url);
            } catch (Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form->createView(),
            'action' => 'Importieren',
            'header' => 'Schüler und Schülerinnen importieren'
        ]);
    }
}
