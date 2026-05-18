<?php

namespace App\Controller\Admin;

use App\Entity\Country;
use App\Entity\FareLevel;
use App\Entity\Order;
use App\Entity\PaymentInterval;
use App\Entity\School;
use App\Entity\Stop;
use App\Entity\Student;
use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{

    public function __construct(
        #[Autowire(env: 'APP_NAME')] private readonly string $appName
    ) {

    }

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->appName)
            ->setLocales(['de']);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Einstellungen')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Allgemeine Einstellungen', 'fas fa-cogs', 'admin_app_settings')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Profileinstellungen', 'fa-solid fa-address-card', 'admin_profile_settings')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Bestellungen', 'fas fa-shopping-basket', 'admin_order_settings')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Import', 'fas fa-upload', 'admin_import_settings')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Export', 'fas fa-download', 'admin_export_settings')->setPermission('ROLE_ADMIN');

        yield MenuItem::section('Kataloge')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkTo(CountryCrudController::class, 'Länder', 'fas fa-flag')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkTo(TicketCrudController::class, 'Tickets', 'fas fa-ticket')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkTo(FareLevelCrudController::class, 'Preisstufen', 'fas fa-ticket')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkTo(PaymentIntervalCrudController::class, 'Zahlungsintervalle', 'fas fa-credit-card')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkTo(StopCrudController::class, 'Haltestellen', 'fas fa-bus-simple')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkTo(SchoolCrudController::class, 'Schulen', 'fas fa-school')->setPermission('ROLE_ADMIN');


        yield MenuItem::section('Stammdaten')->setPermission('ROLE_STUDENT_ADMIN');
        yield MenuItem::linkTo(StudentCrudController::class, 'Schülerinnen und Schüler', 'fas fa-user-graduate')->setPermission('ROLE_STUDENT_ADMIN');

        yield MenuItem::section('Bestellungen')->setPermission('ROLE_ORDER_ADMIN');
        yield MenuItem::linkTo(OrderCrudController::class, 'Bestellungen', 'fa fa-shopping-basket')->setPermission('ROLE_ORDER_ADMIN');
    }
}
