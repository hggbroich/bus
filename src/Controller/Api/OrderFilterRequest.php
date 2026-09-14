<?php

namespace App\Controller\Api;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

readonly class OrderFilterRequest {
    public function __construct(
        #[Assert\NotNull]
        public DateTime|null $start = null,

        #[Assert\NotNull]
        #[Assert\GreaterThanOrEqual(propertyPath: 'start')]
        public DateTime|null $end = null
    ) { }
}
