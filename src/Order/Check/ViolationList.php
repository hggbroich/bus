<?php

namespace App\Order\Check;

readonly class ViolationList {
    public function __construct(
        public int $orderId,
        public array $violations = [ ]
    ) {

    }

    public function hasViolations(): bool {
        return count($this->violations) > 0;
    }
}
