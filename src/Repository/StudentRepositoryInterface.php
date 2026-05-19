<?php

namespace App\Repository;

use App\Entity\Student;

interface StudentRepositoryInterface extends TransactionalRepositoryInterface {

    public function findOneByUuid(string $uuid): ?Student;

    /**
     * @return Student[]
     */
    public function findAll(): array;

    /**
     * @param string $status
     * @return Student[]
     */
    public function findAllByStatus(string $status): array;

    /**
     * @param string[] $emailsOrIds
     * @return Student[]
     */
    public function findAllByEmailOrId(array $emailsOrIds): array;

    /**
     * @return string[]
     */
    public function findAllCities(): array;

    public function persist(Student $student): void;

    public function remove(Student $student): void;
}
