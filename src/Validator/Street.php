<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Street extends Constraint {

    public string $message = 'Street {{ name }} is not recognized. Did you misspelled it? {{ related }}';

    public function __construct(
        public string $plzPropertyPath,
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
