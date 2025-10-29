<?php

namespace App\Export\Order;

use DateTime;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class ExportRequest {

    #[Assert\File]
    #[Assert\NotNull]
    public File|null $csv = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 1)]
    public string $delimiter = ';';

    #[Assert\NotNull]
    public DateTime $startDate;

    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual(propertyPath: 'startDate')]
    public DateTime $endDate;

    #[Assert\NotBlank]
    public string $studentIdHeader = 'Externe_Nr';

    #[Assert\NotBlank]
    public string $customerIdHeader = 'Kundennummer';

    #[Assert\NotBlank]
    public string $ticketHeader = 'Produkt1';

    #[Assert\NotBlank]
    public string $fareLevelHeader = 'Preisstufe';

    #[Assert\NotBlank]
    public string $firstnameHeader = 'Vorname';

    #[Assert\NotBlank]
    public string $lastnameHeader = 'Nachname';

    #[Assert\NotBlank]
    public string $streetHeader = 'Strasse';

    #[Assert\NotBlank]
    public string $houseNumberHeader = 'Hausnummer';

    #[Assert\NotBlank]
    public string $houseNumberSuffixHeader = 'Hausnrzusatz';

    #[Assert\NotBlank]
    public string $plzHeader = 'PLZ';

    #[Assert\NotBlank]
    public string $cityHeader = 'Ort';

    #[Assert\NotBlank]
    public string $countryHeader = 'Land';

    #[Assert\NotBlank]
    public string $phoneNumberHeader = 'Tel_Priv';

    #[Assert\NotBlank]
    public string $emailHeader = 'E_Mail';

    #[Assert\NotBlank]
    public string $birthdayHeader = 'GebDat';

    #[Assert\NotBlank]
    public string $genderHeader = 'Geschlecht';

    #[Assert\NotBlank]
    public string $priceLevelHeader = 'Preisstufe';

    #[Assert\NotBlank]
    public string $ibanHeader = 'IBAN';

    #[Assert\NotBlank]
    public string $depositorFirstnameHeader = 'Kto_Vorname';

    #[Assert\NotBlank]
    public string $depositorLastnameHeader = 'Kto_Nachname';

    #[Assert\NotBlank]
    public string $depositorStreetHeader = 'Kto_Strasse';

    #[Assert\NotBlank]
    public string $depositorHouseNumberHeader = 'Kto_Hausnummer';

    #[Assert\NotBlank]
    public string $depositorHouseNumberSuffixHeader = 'Kto_Hausnrzusatz';

    #[Assert\NotBlank]
    public string $depositorPLZHeader = 'Kto_PLZ';

    #[Assert\NotBlank]
    public string $depositorCityHeader = 'Kto_Ort';

    #[Assert\NotBlank]
    public string $depositorCountryHeader = 'Kto_Land';

    #[Assert\NotBlank]
    public string $depositorBirthdayHeader = 'Kto_GebDat';
}
