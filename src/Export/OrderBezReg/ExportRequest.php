<?php

namespace App\Export\OrderBezReg;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class ExportRequest {
    #[Assert\NotBlank]
    #[Assert\Length(max: 1)]
    public string $delimiter = ';';

    #[Assert\NotBlank]
    public string $status = 'Aktive';

    #[Assert\NotNull]
    public DateTime $startDate;

    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual(propertyPath: 'startDate')]
    public DateTime $endDate;
}
