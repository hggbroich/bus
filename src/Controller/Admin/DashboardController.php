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
        yield MenuItem::linkToRoute('Allgemeine Einstellungen', 'fas fa-cogs', 'app_settings')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Profileinstellungen', 'fa-solid fa-address-card', 'profile_settings')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Bestellungen', 'fas fa-shopping-basket', 'order_settings')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Import', 'fas fa-upload', 'import_settings')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('Export', 'fas fa-download', 'export_settings')->setPermission('ROLE_ADMIN');

        yield MenuItem::section('Kataloge')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Länder', 'fas fa-flag', Country::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Tickets', 'fas fa-ticket', Ticket::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Preisstufen', 'fas fa-ticket', FareLevel::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Zahlungsintervalle', 'fas fa-credit-card', PaymentInterval::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Haltestellen', 'fas fa-bus-simple', Stop::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Schulen', 'fas fa-school', School::class)->setPermission('ROLE_ADMIN');


        yield MenuItem::section('Stammdaten')->setPermission('ROLE_STUDENT_ADMIN');
        yield MenuItem::linkToCrud('Schülerinnen und Schüler', 'fas fa-user-graduate', Student::class)->setPermission('ROLE_STUDENT_ADMIN');

        yield MenuItem::section('Bestellungen')->setPermission('ROLE_ORDER_ADMIN');
        yield MenuItem::linkToCrud('Bestellungen', 'fa fa-shopping-basket', Order::class)->setPermission('ROLE_ORDER_ADMIN');
    }
}
