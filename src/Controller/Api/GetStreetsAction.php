<?php

namespace App\Controller\Api;

use App\Repository\StreetRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetStreetsAction extends AbstractController {

    #[Route('/api/streets')]
    public function __invoke(
        StreetRepositoryInterface $streetRepository
    ): JsonResponse {
        $streets = $streetRepository->findAll();
        return $this->json($streets);
    }
}
