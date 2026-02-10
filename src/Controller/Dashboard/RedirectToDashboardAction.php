<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class RedirectToDashboardAction extends AbstractController {

    #[Route('/', name: 'index')]
    public function __invoke(#[CurrentUser] User $user): Response {
        if($user->getAssociatedStudents()->count() === 0) {
            $rolesToCheck = [ 'ROLE_ADMIN', 'ROLE_ORDER_ADMIN', 'ROLE_STUDENT_ADMIN' ];

            if (array_any($rolesToCheck, fn($role) => $this->isGranted($role))) {
                return $this->redirectToRoute('admin');
            }
        }

        return $this->redirectToRoute('dashboard');
    }
}
