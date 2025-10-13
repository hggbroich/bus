<?php

namespace App\Doctrine;

use App\Entity\Order;
use App\Ticket\TicketManager;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preFlush, entity: Order::class)]
readonly class OrderPlacedEventSubscriber {

    public function __construct(private TicketManager $ticketManager) {

    }

    public function preFlush(Order $order, PreFlushEventArgs $args): void {
        $this->ticketManager->setCorrectTicket($order);
    }
}

