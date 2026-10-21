<?php

namespace App\Order\Continuation;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class ContinueOrderMessage {

    /**
     * @param int $orderId
     * @param string[] $ignoreGrades
     */
    public function __construct(
        public int $orderId,
        public array $ignoreGrades
    ) { }
}
