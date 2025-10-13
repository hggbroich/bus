<?php

namespace App\Command;

use App\Import\Schools\SvwsGitHubSchoolsImporter;
use App\Settings\ImportSettings;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand('app:import:schools', description: 'Lädt die Liste mit Schulen in die Datenbank.')]
readonly class DownloadSchoolsCommand {
    public function __construct(private SvwsGitHubSchoolsImporter $importer, private ImportSettings $importSettings) { }

    public function __invoke(SymfonyStyle $style): int {
        $style->section('Lade Schulen aus dem SVWS-Repository herunter');
        $style->info(sprintf('Download-URL: %s', $this->importSettings->schoolsImportUrl));

        $result = $this->importer->import();

        $style->success(sprintf('%d Schulen neu importiert und %d Schulen aktualisiert.', $result->added, $result->updated));
        return Command::SUCCESS;
    }
}
