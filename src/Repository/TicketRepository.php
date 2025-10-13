<?php

namespace App\Repository;

use App\Entity\Ticket;
use Override;

class TicketRepository extends AbstractRepository implements TicketRepositoryInterface {

    #[Override]
    public function findAll(): array {
        return $this->em->getRepository(Ticket::class)->findAll();
    }
}
