<?php

namespace App\Ticket;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Repository\TicketRepositoryInterface;
use App\Utils\ArrayUtils;

class TicketManager {

    public const array Map = [
        0 => 'SF-1-K',
        1 => 'SF-2-K',
        2 => 'SF-3-K'
    ];

    public const string BackupTicket = 'SF-3-K';

    public function __construct(private readonly TicketRepositoryInterface $ticketRepository) { }

    public function setCorrectTicket(Order $order): void {
        $tickets = ArrayUtils::createArrayWithKeys(
            $this->ticketRepository->findAll(),
            fn(Ticket $ticket): string => $ticket->getName()
        );

        $siblings = [
            new Sibling(
                $order->getStudent()->getFirstname(),
                $order->getStudent()->getLastname(),
                $order->getStudent()->getBirthday(),
                $order->getStudent()
            )
        ];

        foreach($order->getSiblings() as $sibling) {
            $siblings[] = new Sibling(
                $sibling->getFirstname(),
                $sibling->getLastname(),
                $sibling->getBirthday(),
                $sibling->getStudentAtSchool()
            );
        }

        usort($siblings, function(Sibling $a, Sibling $b): int {
            if($a->birthday === $b->birthday) {
                return strnatcmp($a->firstname, $b->firstname);
            }

            return $a->birthday > $b->birthday ? 1 : -1;
        });

        $siblingPos = null;

        for($idx = 0; $idx < count($siblings); $idx++) {
            if($siblings[$idx]->student === $order->getStudent()) {
                $siblingPos = $idx;
                break;
            }
        }

        if($siblingPos === null) {
            return;
        }

        // Set correct ticket
        $ticketName = self::Map[$siblingPos] ?? self::BackupTicket;
        $ticket = $tickets[$ticketName] ?? null;

        if($ticket !== null) {
            $order->setTicket($ticket);
        }
    }
}
