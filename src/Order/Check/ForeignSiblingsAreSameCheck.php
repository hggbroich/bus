<?php

namespace App\Order\Check;

use App\Entity\Order;
use App\Entity\StudentSibling;
use App\Repository\OrderRepositoryInterface;
use App\Settings\OrderSettings;
use App\Utils\ArrayUtils;
use Override;
use SchulIT\CommonBundle\Helper\DateHelper;

readonly class ForeignSiblingsAreSameCheck implements CheckInterface {

    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderSettings $orderSettings,
        private DateHelper $dateHelper
    ) {

    }

    #[Override]
    public function check(Order $order): array {
        if($this->orderSettings->windowStart === null || $this->orderSettings->windowEnd === null) {
            return [ ];
        }

        if(!$this->dateHelper->isBetween($order->getCreatedAt(), $this->orderSettings->windowStart, $this->orderSettings->windowEnd)) {
            return [ ];
        }

        $filterFunction = fn(StudentSibling $sibling): bool => $sibling->getStudentAtSchool() === null;
        $mapFunction = fn(StudentSibling $sibling): string => sprintf('%s-%s-%-%s', $sibling->getFirstname(), $sibling->getLastname(), $sibling->getSchool()->getId(), $sibling->getBirthday()->format('Y-m-d'));
        $thisForeignSiblings = $order
            ->getSiblings()
            ->filter($filterFunction)
            ->map($mapFunction)
            ->toArray();

        $violations = [ ];

        foreach($order->getSiblings() as $sibling) {
            if($sibling->getStudentAtSchool() === null) {
                continue;
            }

            $siblingOrder = $this->orderRepository->findForStudentInRange($sibling->getStudentAtSchool(), $this->orderSettings->windowStart, $this->orderSettings->windowEnd);

            if($siblingOrder === null) {
                continue;
            }

            $otherForeignSiblings = $siblingOrder
                ->getSiblings()
                ->filter($filterFunction)
                ->map($mapFunction)
                ->toArray();

            if(!ArrayUtils::areEqual($otherForeignSiblings, $thisForeignSiblings)) {
                $violations[] = new Violation($siblingOrder->getId(), 'orders.checks.foreign_siblings.message');
            }
        }

        return $violations;
    }
}
