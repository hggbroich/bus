<?php

namespace App\Repository;

use App\Entity\Stop;
use Override;

class StopRepository extends AbstractTransactionalRepository implements StopRepositoryInterface {

    #[Override]
    public function findAll(): array {
        return $this->em->getRepository(Stop::class)->findAll();
    }

    #[Override]
    public function findAllExternal(): array {
        return $this->em->createQueryBuilder()
            ->select('s')
            ->from(Stop::class, 's')
            ->where('s.externalId IS NOT NULL')
            ->getQuery()
            ->getResult();
    }

    #[Override]
    public function persist(Stop $stop): void {
        $this->em->persist($stop);
        $this->flushIfNotInTransaction();
    }

    #[Override]
    public function remove(Stop $stop): void {
        $this->em->remove($stop);
        $this->flushIfNotInTransaction();
    }
}
