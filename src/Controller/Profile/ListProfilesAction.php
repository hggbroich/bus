<?php

namespace App\Controller\Profile;

use App\Security\Voter\ProfileVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListProfilesAction extends AbstractController {

    #[Route('/profile', name: 'profile')]
    public function __invoke(): Response {
        $this->denyAccessUnlessGranted(ProfileVoter::ViewList);
        return $this->render('profile/index.html.twig');
    }
}
