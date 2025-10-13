<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedirectToDashboardAction extends AbstractController {

    #[Route('/', name: 'index')]
    public function __invoke(): Response {
        return $this->redirectToRoute('dashboard');
    }
}
