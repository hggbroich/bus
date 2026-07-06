<?php

namespace App\Command;

use App\Import\Cities\CitiesImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:import:cities')]
readonly class DownloadCitiesCommand {
    public function __construct(
        private CitiesImporter $importer
    ) {

    }

    public function __invoke(
        SymfonyStyle $io
    ): int {
        $this->importer->import();

        return Command::SUCCESS;
    }
}
