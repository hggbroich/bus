<?php

namespace App\Repository;

use App\Entity\Stop;

interface StopRepositoryInterface extends TransactionalRepositoryInterface {
    /**
     * @return Stop[]
     */
    public function findAll(): array;

    /**
     * @return Stop[]
     */
    public function findAllExternal(): array;

    public function persist(Stop $stop): void;

    public function remove(Stop $stop): void;
}
