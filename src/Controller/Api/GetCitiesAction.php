<?php

namespace App\Controller\Api;

use App\Repository\CityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetCitiesAction extends AbstractController {

    #[Route('/api/cities')]
    public function __invoke(
        CityRepositoryInterface $cityRepository
    ): JsonResponse {
        $cities = $cityRepository->findAll();
        return $this->json($cities);
    }
}
