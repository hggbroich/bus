<?php

namespace App\Repository;

use App\Entity\School;

interface SchoolRepositoryInterface extends TransactionalRepositoryInterface {

    /**
     * @return School[]
     */
    public function findAll(): array;

    public function findById(int $id): ?School;

    public function persist(School $school): void;

    public function remove(School $school): void;
}
