<?php

namespace App\Repository;

use App\Entity\City;
use Override;

class CityRepository extends AbstractTransactionalRepository implements CityRepositoryInterface {

    #[Override]
    public function findByPlz(string $plz): ?City {
        return $this->em->getRepository(City::class)->findOneBy(['plz' => $plz]);
    }

    #[Override]
    public function findAll(): array {
        return $this->em->getRepository(City::class)->findAll();
    }

    #[Override]
    public function persist(City $city): void {
        $this->em->persist($city);
        $this->flushIfNotInTransaction();
    }

    #[Override]
    public function remove(City $city): void {
        $this->em->remove($city);
        $this->flushIfNotInTransaction();
    }
}
