<?php

namespace App\Order\Check;

use App\Entity\Order;

readonly class Violation {
    public function __construct(
        public int|null $otherOrderId,
        public string $messageKey,
        public array $messageParameters = [ ],
    ) { }
}
