<?php

namespace App\Ticket;

use App\Entity\Order;
use App\Settings\OrderSettings;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class TicketManager {

    /**
     * @param OrderSettings $orderSettings
     * @param AssignmentStrategyInterface[] $strategies
     */
    public function __construct(
        private OrderSettings $orderSettings,
        #[AutowireIterator(AssignmentStrategyInterface::AUTOCONFIGURE_TAG)] private iterable $strategies
    ) { }

    /**
     * @return AssignmentStrategyInterface[]
     */
    public function getStrategies(): iterable {
        return $this->strategies;
    }

    public function assignTicket(Order $order): void {
        foreach($this->strategies as $strategy) {
            if(get_class($strategy) === $this->orderSettings->assignmentStrategy) {
                $strategy->assign($order);
            }
        }
    }
}
