<?php

namespace App\Repository;

use App\Entity\FareLevel;
use Override;

class FareLevelRepository extends AbstractTransactionalRepository implements FareLevelRepositoryInterface {

    #[Override]
    public function findOneByCity(string $city): ?FareLevel {
        return $this->em
            ->getRepository(FareLevel::class)
            ->findOneBy(['city' => $city]);
    }

    #[Override]
    public function persist(FareLevel $fareLevel): void {
        $this->em->persist($fareLevel);
        $this->flushIfNotInTransaction();
    }
}
