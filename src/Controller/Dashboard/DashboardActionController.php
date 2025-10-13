<?php

declare(strict_types=1);

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Profile\ProfileCompleteChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class DashboardActionController extends AbstractController {
    #[Route('/dashboard', name: 'dashboard')]
    public function __invoke(#[CurrentUser] User $user, ProfileCompleteChecker $profileChecker): Response {
        $violationLists = [ ];

        foreach($user->getAssociatedStudents() as $student) {
            $violationLists[$student->getId()] = $profileChecker->check($student);
        }

        return $this->render('dashboard/index.html.twig', [
            'violationLists' => $violationLists,
        ]);
    }
}
