<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class NotAMinor extends Constraint {
    public string $message = 'Deposit owner must not be a minor at {{ date }}.';

    public function __construct(mixed $options = null, ?array $groups = null, mixed $payload = null) {
        parent::__construct($options, $groups, $payload);
    }
}
