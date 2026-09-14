<?php

namespace App\Repository;

use App\Entity\FareLevel;

interface FareLevelRepositoryInterface extends TransactionalRepositoryInterface {

    public function findOneByCity(string $city): ?FareLevel;

    /**
     * @return FareLevel[]
     */
    public function findAll(): array;

    public function persist(FareLevel $fareLevel): void;
}
