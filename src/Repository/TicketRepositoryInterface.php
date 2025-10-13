<?php

namespace App\Repository;

use App\Entity\Ticket;

interface TicketRepositoryInterface {

    /**
     * @return Ticket[]
     */
    public function findAll(): array;
}
