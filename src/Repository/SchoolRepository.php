<?php

namespace App\Repository;

use App\Entity\School;
use Override;

class SchoolRepository extends AbstractTransactionalRepository implements SchoolRepositoryInterface {

    #[Override]
    public function findAll(): array {
        return $this->em->getRepository(School::class)->findAll();
    }

    #[Override]
    public function persist(School $school): void {
        $this->em->persist($school);
        $this->flushIfNotInTransaction();
    }

    #[Override]
    public function remove(School $school): void {
        $this->em->remove($school);
        $this->flushIfNotInTransaction();
    }
}
