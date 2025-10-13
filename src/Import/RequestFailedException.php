<?php

namespace App\Import;

use Exception;
use Throwable;

class RequestFailedException extends Exception {
    public function __construct(private readonly string $content, string $message = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function getContent(): string {
        return $this->content;
    }
}
