<?php

namespace App\Import\Cities;

use App\Entity\City;
use App\Entity\Street;
use App\Repository\CityRepositoryInterface;
use App\Repository\StreetRepositoryInterface;
use App\Utils\ArrayUtils;

class StreetsImporter {
    public function __construct(
        private OpenPlzApiClient $plzApiClient,
        private CityRepositoryInterface $cityRepository,
        private StreetRepositoryInterface $streetRepository,
    ) { }

    public function importAll(): void {
        foreach($this->cityRepository->findAll() as $city) {
            $this->import($city);
        }
    }

    public function import(City $city): void {
        $streets = ArrayUtils::createArrayWithKeys(
            $this->streetRepository->findAllByCity($city),
            fn(Street $street) => $street->getName()
        );

        $this->streetRepository->beginTransaction();

        foreach($this->getStreets($city->getPlz()) as $street) {
            $existingStreet = $streets[$street->name] ?? null;

            if($existingStreet === null) {
                $existingStreet = new Street();
                $existingStreet->setName($street->name);
                $existingStreet->setCity($city);
            }

            $this->streetRepository->persist($existingStreet);
        }

        $this->streetRepository->commit();
    }

    /**
     * @param string $plz
     * @return \OpenPlzApi\DE\Street[]
     */
    private function getStreets(string $plz): array {
        $streets = [ ];

        $response = $this->plzApiClient->getStreets(null, $plz, null);

        do {
            $streets = array_merge($streets, $response->toArray());
        } while(($response = $response->getNextPage()) !== null);

        return $streets;
    }
}
