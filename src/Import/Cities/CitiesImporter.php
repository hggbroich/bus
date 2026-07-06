<?php

namespace App\Import\Cities;

use App\Entity\City;
use App\Repository\CityRepositoryInterface;
use App\Settings\ImportSettings;
use App\Utils\ArrayUtils;
use OpenPlzApi\DE\Locality;

readonly class CitiesImporter {
    public function __construct(
        private OpenPlzApiClient $plzApiClient,
        private CityRepositoryInterface $cityRepository,
        private ImportSettings $importSettings,
    ) {

    }

    public function import(): void {
        $cities = ArrayUtils::createArrayWithKeys(
            $this->cityRepository->findAll(),
            fn(City $city): string => $city->getPlz()
        );

        $this->cityRepository->beginTransaction();

        foreach($this->getLocalities() as $locality) {
            if($this->importSettings->importPlzStart !== null && $this->importSettings->importPlzEnd !== null && ($this->importSettings->importPlzStart > $locality->postalCode || $this->importSettings->importPlzEnd < $locality->postalCode)) {
                continue;
            }

            $existingCity = $cities[$locality->postalCode] ?? null;

            if($existingCity === null) {
                $existingCity = new City();
                $existingCity->setPlz($locality->postalCode);
            }

            $existingCity->setName($locality->name);
            $existingCity->setFederalState($locality->federalState->name);

            $this->cityRepository->persist($existingCity);
        }

        $this->cityRepository->commit();
    }

    /**
     * @return Locality[]
     */
    private function getLocalities(): array {
        $localities = [];

        $response = $this->plzApiClient->getLocalitiesByFederalState($this->importSettings->federalState);

        do {
            $localities = array_merge($localities, $response->toArray());
        } while(($response = $response->getNextPage()) !== null);

        return $localities;
    }
}
