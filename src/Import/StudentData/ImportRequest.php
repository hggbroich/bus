<?php

namespace App\Import\StudentData;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class ImportRequest {
    #[Assert\File]
    #[Assert\NotNull]
    public File|null $csv = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 1)]
    public string $delimiter = ';';

    #[Assert\NotBlank]
    public string $studentIdHeader = 'Interne ID-Nummer';

    #[Assert\NotBlank]
    public string $publicSchoolHeader = "Öffentliche Schule";

    #[Assert\NotBlank]
    public string $distancePublicSchoolHeader = "Distanz öffentliche Schule";

    #[Assert\NotBlank]
    public string $distanceSchoolHeader = "Distanz Schule";

}
