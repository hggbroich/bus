<?php

namespace App\Order\Continuation;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class ContinueOrdersRequest {

    #[Assert\NotNull]
    public DateTime|null $windowStart = null;

    #[Assert\NotNull]
    #[Assert\GreaterThan(propertyPath: 'windowStart')]
    public DateTime|null $windowEnd = null;

    /** @var string[] */
    #[Assert\All([
        new Assert\NotBlank
    ])]
    public array $excludeGrades = [ ];
}
