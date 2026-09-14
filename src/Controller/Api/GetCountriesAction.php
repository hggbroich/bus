<?php

namespace App\Controller\Api;

use App\Repository\CountryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetCountriesAction extends AbstractController {

    #[Route('/api/countries')]
    public function __invoke(
        CountryRepositoryInterface $countryRepository,
    ): JsonResponse {
        $countries = $countryRepository->findAll();
        return $this->json($countries);
    }
}
