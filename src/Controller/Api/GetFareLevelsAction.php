<?php

namespace App\Controller\Api;

use App\Repository\FareLevelRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetFareLevelsAction extends JsonResponse {

    #[Route('/api/far-levels')]
    public function __invoke(
        FareLevelRepositoryInterface $fareLevelRepository
    ): JsonResponse{
        $levels = $fareLevelRepository->findAll();
        return new JsonResponse($levels);
    }
}
