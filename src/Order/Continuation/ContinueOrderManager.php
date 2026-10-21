<?php

namespace App\Order\Continuation;

use App\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class ContinueOrderManager {
    public function __construct(
        private MessageBusInterface $messageBus,
        private OrderRepositoryInterface $orderRepository
    ) {

    }

    public function continueOrders(ContinueOrdersRequest $request): int {
        $orders = $this->orderRepository->findAllRange(
            $request->windowStart,
            $request->windowEnd
        );

        foreach($orders as $order) {
            $message = new ContinueOrderMessage($order->getId(), $request->excludeGrades);
            $this->messageBus->dispatch($message);
        }

        return count($orders);
    }
}
