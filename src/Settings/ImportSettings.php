<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

#[Settings]
class ImportSettings {
    use SettingsTrait;

    #[SettingsParameter(label: 'settings.import.schools.url.label', description: 'settings.import.schools.url.help', formType: UrlType::class, nullable: false)]
    public string $schoolsImportUrl = "https://github.com/SVWS-NRW/SVWS-Server/raw/refs/heads/dev/svws-db/src/main/resources/daten/csv/schulver/Schulen.csv";

    #[SettingsParameter(label: 'settings.import.schools.start_plz.label', description: 'settings.import.schools.end_plz.help', formType: IntegerType::class, nullable: true)]
    public int|null $importPlzStart = null;

    #[SettingsParameter(label: 'settings.import.schools.end_plz.label', description: 'settings.import.schools.end_plz.help', formType: IntegerType::class, nullable: true)]
    public int|null $importPlzEnd = null;

    #[SettingsParameter(label: 'settings.import.stops.url.label', description: 'settings.import.stops.url.help', formType: UrlType::class, nullable: false)]
    public string $stopsImportUrl = "https://opendata.avv.de/current_GTFS/AVV_GTFS_mit_SPNV.zip";
}
