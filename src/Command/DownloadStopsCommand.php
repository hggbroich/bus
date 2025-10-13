<?php

namespace App\Command;

use App\Import\Stops\GtfsStopsImporter;
use App\Settings\ImportSettings;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:import:stops', description: 'Lädt eine Liste von Haltestellen herunter.')]
readonly class DownloadStopsCommand {
    public function __construct(private GtfsStopsImporter $importer, private ImportSettings $importSettings) { }

    public function __invoke(SymfonyStyle $style): int {
        $style->section('Lade GTFS-Daten herunter');
        $style->info(sprintf('Download-URL: %s', $this->importSettings->stopsImportUrl));

        $result = $this->importer->import();

        $style->success(sprintf('%d Haltestellen neu importiert und %d Haltestellen aktualisiert.', $result->added, $result->updated));
        return Command::SUCCESS;
    }
}
