<?php

namespace App\Command;

use App\Import\Cities\StreetsImporter;
use App\Repository\CityRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand('app:import:streets')]
#[AsCronTask('@daily')]
readonly class DownloadStreetsCommand {
    public function __construct(
        private StreetsImporter $importer,
        private CityRepositoryInterface $cityRepository
    ) { }

    public function __invoke(
        SymfonyStyle $io
    ): int {
        foreach($this->cityRepository->findAll() as $city) {
            $io->section(sprintf('Importiere Straßen für: %s', $city->getName()));
            $this->importer->import($city);
        }

        $io->success('Fertig');

        return Command::SUCCESS;
    }
}
