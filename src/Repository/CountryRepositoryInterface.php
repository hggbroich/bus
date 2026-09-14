<?php

namespace App\Repository;

use App\Entity\Country;

interface CountryRepositoryInterface {

    /**
     * @return Country[]
     */
    public function findAll(): array;
}
