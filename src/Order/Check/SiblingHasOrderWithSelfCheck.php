<?php

namespace App\Order\Check;

use App\Entity\Order;
use App\Repository\OrderRepositoryInterface;
use App\Settings\OrderSettings;
use Override;
use SchulIT\CommonBundle\Helper\DateHelper;

readonly class SiblingHasOrderWithSelfCheck implements CheckInterface {

    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderSettings $orderSettings,
        private DateHelper $dateHelper
    ) { }

    #[Override]
    public function check(Order $order): array {
        if($this->orderSettings->windowStart === null || $this->orderSettings->windowEnd === null) {
            return [ ];
        }

        if(!$this->dateHelper->isBetween($order->getCreatedAt(), $this->orderSettings->windowStart, $this->orderSettings->windowEnd)) {
            return [ ];
        }

        $violations = [ ];

        foreach($order->getSiblings() as $sibling) {
            if($sibling->getStudentAtSchool() === null) {
                continue;
            }

            $otherOrder = $this->orderRepository->findForStudentInRange(
                $sibling->getStudentAtSchool(),
                $this->orderSettings->windowStart,
                $this->orderSettings->windowEnd
            );

            if($otherOrder === null) {
                continue;
            }

            $selfIncluded = false;

            foreach($otherOrder->getSiblings() as $otherSibling) {
                if($otherSibling->getStudentAtSchool()?->getId() === $order->getStudent()->getId()) {
                    $selfIncluded = true;
                }
            }

            if($selfIncluded === false) {
                $violations[] = new Violation('orders.checks.self_included.message');
            }
        }

        return $violations;
    }
}
