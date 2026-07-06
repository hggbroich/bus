<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Plz extends Constraint {
    public string $message = 'Postal code {{ code }} is outside allowed area.';

    public function __construct(
        public string $countryPropertyName,
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
