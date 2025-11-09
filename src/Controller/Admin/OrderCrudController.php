<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Export\Order\Exporter;
use App\Export\Order\ExportRequest;
use App\Export\Order\ExportRequestType;
use App\Form\StudentSiblingType;
use App\Order\Check\OrderChecker;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ORDER_ADMIN')]
class OrderCrudController extends AbstractCrudController
{

    public function __construct(
        private readonly AdminUrlGenerator $urlGenerator
    ) {

    }

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
            ->add('ticket')
            ->add('createdAt')
            ->add('isIncorrect');
    }

    public function configureActions(Actions $actions): Actions {
        $exportAction = Action::new('export', 'Exportieren', 'fa-solid fa-download')
            ->linkToCrudAction('export')
            ->createAsGlobalAction();

        $checkAction = Action::new('check', 'Bestellungen prüfen', 'fa-solid fa-clipboard-check')
            ->linkToCrudAction('checkOrders')
            ->createAsGlobalAction();

        $showInvalidAction = Action::new('incorrect', 'Ungültige Bestellungen anzeigen', 'fas fa-exclamation-triangle')
            ->linkToCrudAction('showIncorrect')
            ->createAsGlobalAction();

        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $exportAction)
            ->add(Crud::PAGE_INDEX, $checkAction)
            ->add(Crud::PAGE_INDEX, $showInvalidAction)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable {
        return [
            FormField::addTab('Schülerdaten'),
            FormField::addColumn(12),
            AssociationField::new('student', 'Schüler/Schülerin')
                ->setDisabled(),

            FormField::addColumn(6),
            TextField::new('firstname', 'Vorname'),

            FormField::addColumn(6),
            TextField::new('lastname', 'Nachname'),

            FormField::addColumn(6),
            DateField::new('birthday', 'Geburtstag')
                ->hideOnIndex(),

            FormField::addColumn(6),
            ChoiceField::new('gender', 'Geschlecht')
                ->hideOnIndex(),

            FormField::addColumn(6),
            TextField::new('street', 'Strasse')
                ->hideOnIndex(),

            FormField::addColumn(6),
            TextField::new('houseNumber', 'Hausnummer')
                ->hideOnIndex(),

            FormField::addColumn(6),
            IntegerField::new('plz', 'PLZ')
                ->hideOnIndex(),

            FormField::addColumn(6),
            TextField::new('city', 'Ort')
                ->hideOnIndex(),

            FormField::addTab('Informationen zum Ticket'),

            FormField::addColumn(6),
            AssociationField::new('ticket', 'Ticket'),

            FormField::addColumn(6),
            TextField::new('busCompanyCustomerId', 'Kundennummer Busunternehmen'),

            FormField::addColumn(6),
            AssociationField::new('stop', 'Haltestelle')
                ->hideOnIndex(),

            FormField::addColumn(6),
            AssociationField::new('fareLevel', 'Preisstufe')
                ->setDisabled()
                ->setHelp('Die Preisstufe wird automatisch anhand des Wohnortes des Kindes ermittelt und automatisch gesetzt. Bereits vorab ausgewählte Werte werden überschrieben.')
                ->hideOnIndex(),

            FormField::addColumn(6),
            AssociationField::new('paymentInterval', 'Zahlungsintervall')
                ->hideOnIndex(),

            FormField::addColumn(6),
            BooleanField::new('sgb12', 'SGB12')
                ->hideOnIndex(),

            FormField::addTab('Kontodaten'),

            FormField::addColumn(6),
            TextField::new('depositorFirstname', 'Vorname')
                ->hideOnIndex(),

            FormField::addColumn(6),
            TextField::new('depositorLastname', 'Nachname')
                ->hideOnIndex(),

            FormField::addColumn(6),
            DateField::new('depositorBirthday', 'Geburtstag')
                ->hideOnIndex(),

            FormField::addColumn(6),
            TextField::new('depositorStreet', 'Straße')
                ->hideOnIndex(),

            FormField::addColumn(6),
            TextField::new('depositorHouseNumber', 'Hausnummer')
                ->hideOnIndex(),

            FormField::addColumn(6),
            IntegerField::new('depositorPlz', 'PLZ')
                ->hideOnIndex(),

            FormField::addColumn(6),
            AssociationField::new('depositorCountry', 'Land')
                ->hideOnIndex(),

            FormField::addColumn(6),
            TextField::new('depositorPhoneNumber', 'Telefonnummer')
                ->hideOnIndex(),
            FormField::addColumn(6),
            EmailField::new('depositorEmail', 'E-Mail-Adresse')
                ->hideOnIndex(),
            FormField::addColumn(6),
            TextField::new('iban', 'IBAN')
                ->setHelp('Die IBAN nach dem Abschicken des Formulars verschlüsselt und liegt online nicht mehr im Klartext vor. Wenn die IBAN hier nicht geändert wird, bleibt der verschlüsselte Wert erhalten.')
                ->hideOnIndex(),
            FormField::addColumn(6),

            FormField::addTab('Geschwister'),
            CollectionField::new('siblings', 'Geschwister')
                ->hideOnIndex()
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex()
                ->useEntryCrudForm()
                ->setFormTypeOption('by_reference', false),

            FormField::addTab('Weitere Details')
                ->hideOnForm(),

            FormField::addColumn(6),
            TextField::new('createdBy', 'Erstellt von')
                ->setDisabled()
                ->hideOnForm(),

            FormField::addColumn(6),
            DateTimeField::new('createdAt', 'Erstellt am')
                ->setDisabled()
                ->hideOnForm(),

            FormField::addColumn(6),
            TextField::new('updatedBy', 'Aktualisiert von')
                ->setDisabled()
                ->hideOnForm(),

            FormField::addColumn(6),
            DateTimeField::new('updatedAt', 'Aktualisiert am')
                ->setDisabled()
                ->hideOnForm(),

            FormField::addColumn(6),
            BooleanField::new('isIncorrect', 'Fehlerhafte Bestellung')
                ->setDisabled()
                ->hideOnForm(),
            DateTimeField::new('lastCheckedAt', 'Letzte Prüfung')
                ->setDisabled()
                ->hideOnForm(),
        ];
    }

    #[AdminRoute('/export')]
    public function export(Exporter $exporter, Request $request): BinaryFileResponse|Response {
        $exportRequest = new ExportRequest();
        $form = $this->createForm(ExportRequestType::class, $exportRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            return $exporter->export($exportRequest);
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form->createView(),
            'header' => 'Bestellungen exportieren',
            'action' => 'Exportieren'
        ]);
    }

    #[AdminRoute('/check')]
    public function checkOrders(OrderChecker $orderChecker): RedirectResponse {
        $count = $orderChecker->checkAllInCurrentWindowAsync();

        $this->addFlash('success', sprintf('%d Bestellung(en) wurden zur Prüfung eingereiht. Die Bestellungen werden asynchron geprüft.', $count));

        $url = $this->urlGenerator->setController(OrderCrudController::class)->setAction(Action::INDEX)->generateUrl();
        return $this->redirect($url);
    }

    #[AdminRoute('/incorrect')]
    public function showIncorrect(): RedirectResponse {
        $url = $this->urlGenerator
            ->setController(OrderCrudController::class)
            ->setAction(Action::INDEX)
            ->set('filters[isIncorrect]', '1')
            ->generateUrl();
        return $this->redirect($url);
    }
}
