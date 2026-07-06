<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Street;
use Override;

class StreetRepository extends AbstractTransactionalRepository implements StreetRepositoryInterface {

    #[Override]
    public function findAllByCity(City $city): array {
        return $this->em->getRepository(Street::class)->findBy(['city' => $city]);
    }

    #[Override]
    public function persist(Street $street): void {
        $this->em->persist($street);
        $this->flushIfNotInTransaction();
    }

    #[Override]
    public function remove(Street $street): void {
        $this->em->remove($street);
        $this->flushIfNotInTransaction();
    }
}
