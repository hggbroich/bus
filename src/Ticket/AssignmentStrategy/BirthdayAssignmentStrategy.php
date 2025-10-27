<?php

namespace App\Ticket\AssignmentStrategy;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Repository\TicketRepositoryInterface;
use App\Ticket\AssignmentStrategyInterface;
use App\Utils\ArrayUtils;
use Override;

readonly class BirthdayAssignmentStrategy implements AssignmentStrategyInterface {

    public function __construct(
        private TicketRepositoryInterface $ticketRepository
    ) {

    }

    #[Override]
    public function assign(Order $order): void {
        $tickets = ArrayUtils::createArrayWithKeys(
            $this->ticketRepository->findAll(),
            fn(Ticket $ticket): int => $ticket->getPriority()
        );

        if(count($tickets) === 0) {
            return;
        }

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
        do {
            $ticket = $tickets[$siblingPos] ?? null;
            $siblingPos--; // decrease position so we get the highest matching one
        } while($ticket === null && $siblingPos >= 0);

        if($ticket === null) {
            return;
        }

        $order->setTicket($ticket);
    }

    #[Override]
    public function getTranslationKey(): string {
        return 'settings.orders.ticket_assignment.strategies.birthday.label';
    }

    #[Override]
    public function getTranslationHelpKey(): string {
        return 'settings.orders.ticket_assignment.strategies.birthday.help';
    }
}
