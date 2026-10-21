<?php

namespace App\Import\Cities;

use App\Entity\City;
use App\Entity\Street;
use App\Repository\CityRepositoryInterface;
use App\Repository\StreetRepositoryInterface;
use App\Settings\ImportSettings;
use App\Utils\ArrayUtils;

class StreetsImporter {
    public function __construct(
        private OpenPlzApiClient $plzApiClient,
        private CityRepositoryInterface $cityRepository,
        private StreetRepositoryInterface $streetRepository,
        private ImportSettings $importSettings,
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
            $streetName = $this->replacePartsInStreetName($street->name);
            $existingStreet = $streets[$streetName] ?? null;

            if($existingStreet === null) {
                $existingStreet = new Street();
                $existingStreet->setName($streetName);
                $existingStreet->setCity($city);
            }

            $this->streetRepository->persist($existingStreet);
        }

        $this->streetRepository->commit();
    }

    private function replacePartsInStreetName(string $streetName): string {
        foreach($this->importSettings->replacements as $replacement) {
            $streetName = str_replace($replacement->key, $replacement->value, $streetName);
        }

        return $streetName;
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
