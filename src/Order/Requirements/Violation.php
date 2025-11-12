<?php

namespace App\Order\Requirements;

readonly class Violation {
    public function __construct(
        public string $messageKey,
        public array $messageParameters = [ ],
    ) { }
}
