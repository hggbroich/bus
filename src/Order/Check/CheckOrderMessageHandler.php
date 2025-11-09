<?php

namespace App\Order\Check;

use App\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CheckOrderMessageHandler {

    public function __construct(
        private OrderChecker $orderChecker,
        private OrderRepositoryInterface $orderRepository
    ) {

    }

    public function __invoke(CheckOrderMessage $message): void {
        $order = $this->orderRepository->findOneById($message->orderId);

        if($order === null) {
            return;
        }

        $this->orderChecker->check($order, markOrdersIncorrect: true);
    }
}
