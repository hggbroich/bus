<?php

namespace App\Repository;

use App\Entity\Country;
use Override;

class CountryRepository extends AbstractRepository implements CountryRepositoryInterface {

    #[Override]
    public function findAll(): array {
        return $this->em->getRepository(Country::class)->findAll();
    }
}
