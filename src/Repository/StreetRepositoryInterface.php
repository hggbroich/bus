<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Street;

interface StreetRepositoryInterface extends TransactionalRepositoryInterface {
    public function findAllByCity(City $city): array;

    public function persist(Street $street): void;

    public function remove(Street $street): void;
}
