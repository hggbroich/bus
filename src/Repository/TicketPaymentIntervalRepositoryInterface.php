<?php

namespace App\Repository;

use App\Entity\TicketPaymentInterval;

interface TicketPaymentIntervalRepositoryInterface {

    /**
     * @return TicketPaymentInterval[]
     */
    public function findAll(): array;
}
