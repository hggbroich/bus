<?php

namespace App\Profile;

readonly class ViolationList {
    public function __construct(
        public array $violations,
        public string $messageKey,
        public array $messageParameters = [ ]
    ) {

    }

    public function hasViolations(): bool {
        return count($this->violations) > 0;
    }
}
