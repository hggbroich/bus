<?php

namespace App\Order\Check;

readonly class Violation {
    public function __construct(
        public string $messageKey,
        public array $messageParameters = [ ]
    ) { }
}
