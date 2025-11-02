<?php

namespace App\Order\Check;

use App\Entity\Order;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class OrderChecker {

    /**
     * @param CheckInterface[] $checks
     */
    public function __construct(
        #[AutowireIterator(CheckInterface::AUTOCONFIGURE_TAG)] private iterable $checks,
    ) {

    }

    public function check(Order $order): ViolationList {
        $violations = [];

        foreach ($this->checks as $check) {
            $violations = array_merge(
                $violations,
                $check->check($order)
            );
        }

        return new ViolationList(
            $order->getId(),
            $violations
        );
    }
}
