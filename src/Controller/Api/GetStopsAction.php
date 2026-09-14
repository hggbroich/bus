<?php

namespace App\Controller\Api;

use App\Repository\StopRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetStopsAction extends AbstractController {

    #[Route('/api/stops')]
    public function __invoke(
        StopRepositoryInterface $stopRepository
    ): JsonResponse {
        $stops = $stopRepository->findAll();
        return $this->json($stops);
    }
}
