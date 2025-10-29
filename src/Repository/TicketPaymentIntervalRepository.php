<?php

namespace App\Repository;

use App\Entity\TicketPaymentInterval;
use Override;

class TicketPaymentIntervalRepository extends AbstractRepository implements TicketPaymentIntervalRepositoryInterface {

    #[Override]
    public function findAll(): array {
        return $this->em->getRepository(TicketPaymentInterval::class)->findAll();
    }
}
