<?php

namespace App\Ticket\AssignmentStrategy;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Repository\TicketRepositoryInterface;
use App\Settings\OrderSettings;
use App\Ticket\AssignmentStrategyInterface;
use App\Utils\ArrayUtils;
use DateTime;
use Override;

readonly class BirthdayAndAgeAssignmentStrategy extends BirthdayAssignmentStrategy implements AssignmentStrategyInterface {

    private const int ThresholdAge = 18;
    private const int PriorityForStudentsOlderThanThresholdAge = 0;

    public function __construct(
        TicketRepositoryInterface $ticketRepository,
        private OrderSettings $orderSettings
    ) {
        parent::__construct($ticketRepository);

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

        if($this->calculateAge($order->getBirthday(), $this->orderSettings->effectiveDate) >= self::ThresholdAge) {
            $order->setTicket($tickets[self::PriorityForStudentsOlderThanThresholdAge]);
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

        $siblings = array_filter(
            $siblings,
            fn(Sibling $sibling): bool => $this->calculateAge($sibling->birthday, $this->orderSettings->effectiveDate) < self::ThresholdAge
        );

        $this->assignFromSiblings($order, $siblings);
    }

    #[Override]
    public function getTranslationKey(): string {
        return 'settings.orders.ticket_assignment.strategies.birthday_age.label';
    }

    #[Override]
    public function getTranslationHelpKey(): string {
        return 'settings.orders.ticket_assignment.strategies.birthday_age.help';
    }

    private function calculateAge(DateTime $birthday, DateTime $effectiveDate): int {
        return $effectiveDate->diff($birthday)->y;
    }
}
