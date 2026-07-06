<?php

namespace App\Repository;

use App\Entity\City;

interface CityRepositoryInterface extends TransactionalRepositoryInterface {
    public function findByPlz(string $plz): ?City;

    /**
     * @return City[]
     */
    public function findAll(): array;

    public function persist(City $city): void;

    public function remove(City $city): void;
}
