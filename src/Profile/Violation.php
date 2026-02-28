<?php

namespace App\Profile;

readonly class Violation {
    public function __construct(
        public string $messageKey,
        public array $messageParameters = [ ]
    ) {

    }
}
