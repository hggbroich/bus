<?php

namespace App\Order\Requirements;

final class ViolationList {
    public function __construct(
        public array $violations
    ) { }

    public function hasViolations(): bool {
        return count($this->violations) > 0;
    }
}
