<?php

namespace App\Order\Check;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class CheckOrderMessage {
    public function __construct(public int $orderId) { }
}
