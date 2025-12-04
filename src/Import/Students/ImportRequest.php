<?php

namespace App\Import\Students;

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
    public string $idHeader = 'Interne ID-Nummer';

    #[Assert\NotBlank]
    public string $firstnameHeader = 'Vorname';

    #[Assert\NotBlank]
    public string $lastnameHeader = 'Nachname';

    #[Assert\NotBlank]
    public string $gradeHeader = 'Klasse';

    #[Assert\NotBlank]
    public string $emailHeader = 'E-Mail schulisch)';

    #[Assert\NotBlank]
    public string $streetHeader = 'Straßenname';

    #[Assert\NotBlank]
    public string $houseNumberHeader = 'Hausnummer';

    #[Assert\NotBlank]
    public string $plzHeader = 'Postleitzahl';

    #[Assert\NotBlank]
    public string $cityHeader = 'Ortsname';

    #[Assert\NotBlank]
    public string $entranceDateHeader = 'Aufnahmedatum';

    #[Assert\NotBlank]
    public string $leaveDateHeader = 'Entlassdatum';

    #[Assert\NotBlank]
    public string $statusHeader = 'Status';

    #[Assert\NotBlank]
    public string $birthdayHeader = 'Geburtsdatum';

    #[Assert\NotBlank]
    public string $genderHeader = 'Geschlecht';

    #[Assert\NotBlank]
    public string $maleGenderValue = 'm';

    #[Assert\NotBlank]
    public string $femaleGenderValue = 'w';

    #[Assert\NotBlank]
    public string $diversGenderValue = 'd';

    public bool $remove = false;
}
