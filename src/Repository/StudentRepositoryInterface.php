<?php

namespace App\Repository;

use App\Entity\Student;

interface StudentRepositoryInterface extends TransactionalRepositoryInterface {

    /**
     * @return Student[]
     */
    public function findAll(): array;

    /**
     * @param string[] $emailsOrIds
     * @return Student[]
     */
    public function findAllByEmailOrId(array $emailsOrIds): array;

    public function persist(Student $student): void;

    public function remove(Student $student): void;
}
