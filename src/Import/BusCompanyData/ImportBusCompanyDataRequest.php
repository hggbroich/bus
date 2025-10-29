<?php

namespace App\Import\BusCompanyData;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class ImportBusCompanyDataRequest {

    #[Assert\File]
    #[Assert\NotNull]
    public File|null $csv = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 1)]
    public string $delimiter = ';';

    #[Assert\NotBlank]
    public string $studentIdHeader = 'Externe_Nr';

    #[Assert\NotBlank]
    public string $customerIdHeader = 'Kundennummer';

    #[Assert\NotBlank]
    public string $ticketHeader = 'Produkt1';
}
