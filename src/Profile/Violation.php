<?php

namespace App\Profile;

class Violation {
    public function __construct(
        public string $messageKey,
        public array $messageParameters = [ ]
    ) {

    }
}
