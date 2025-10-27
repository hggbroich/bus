<?php

namespace App\Doctrine;

use App\Entity\Order;
use App\FareLevel\FareLevelSetter;
use App\Ticket\TicketManager;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preFlush, entity: Order::class)]
readonly class OrderPlacedEventSubscriber {

    public function __construct(
        private TicketManager $ticketManager,
        private FareLevelSetter $fareLevelSetter
    ) {

    }

    public function preFlush(Order $order, PreFlushEventArgs $args): void {
        $this->ticketManager->assignTicket($order);
        $this->fareLevelSetter->setFareLevel($order);
    }
}

