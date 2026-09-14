<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Street;

interface StreetRepositoryInterface extends TransactionalRepositoryInterface {

    /**
     * @param City $city
     * @return Street[]
     */
    public function findAllByCity(City $city): array;

    /**
     * @return Street[]
     */
    public function findAll(): array;

    public function persist(Street $street): void;

    public function remove(Street $street): void;
}
