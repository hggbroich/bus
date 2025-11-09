<?php

namespace App\Order\Check;

use App\Entity\Order;
use App\Repository\OrderRepositoryInterface;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class OrderChecker {

    /**
     * @param CheckInterface[] $checks
     */
    public function __construct(
        #[AutowireIterator(CheckInterface::AUTOCONFIGURE_TAG)] private iterable $checks,
        private OrderRepositoryInterface $orderRepository,
        private DateHelper $dateHelper
    ) {
    }

    public function check(Order $order, bool $markOrdersIncorrect = true): ViolationList {
        $violations = [];

        foreach ($this->checks as $check) {
            $violations = array_merge(
                $violations,
                $check->check($order)
            );
        }

        if($markOrdersIncorrect === true && count($violations) > 0) {
            $order->setIsIncorrect(true);
            $order->setLastCheckedAt($this->dateHelper->getNow());
            $this->orderRepository->persist($order);

            foreach ($violations as $violation) {
                if($violation->otherOrderId !== null) {
                    $order = $this->orderRepository->findOneById($violation->otherOrderId);
                    $order->setLastCheckedAt($this->dateHelper->getNow());

                    $order->setIsIncorrect(true);
                    $this->orderRepository->persist($order);
                }
            }
        } else if($markOrdersIncorrect === true && count($violations) === 0) {
            $order->setIsIncorrect(false);
            $this->orderRepository->persist($order);
        }

        return new ViolationList(
            $order->getId(),
            $violations
        );
    }
}
