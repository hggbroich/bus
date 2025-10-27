<?php

namespace App\Repository;

use App\Entity\FareLevel;

interface FareLevelRepositoryInterface extends TransactionalRepositoryInterface {

    public function findOneByCity(string $city): ?FareLevel;

    public function persist(FareLevel $fareLevel): void;
}
