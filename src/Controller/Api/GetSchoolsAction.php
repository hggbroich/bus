<?php

namespace App\Controller\Api;

use App\Repository\SchoolRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetSchoolsAction extends AbstractController {

    #[Route('/api/schools')]
    public function __invoke(
        SchoolRepositoryInterface $schoolRepository
    ): JsonResponse {
        $schools = $schoolRepository->findAll();
        return $this->json($schools);
    }
}
