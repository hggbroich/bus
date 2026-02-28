<?php

namespace App\Import\Schools;

use App\Entity\School;
use App\Import\ImportResult;
use App\Import\RequestFailedException;
use App\Repository\SchoolRepositoryInterface;
use App\Settings\ImportSettings;
use App\Utils\ArrayUtils;
use League\Csv\Reader;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class SvwsGitHubSchoolsImporter {
    public function __construct(private ImportSettings $importSettings, private SchoolRepositoryInterface $schoolRepository, private HttpClientInterface $client) { }

    public function import(): ImportResult {
        $csvAsString = $this->downloadCsv();
        $existingSchools = ArrayUtils::createArrayWithKeys(
            $this->schoolRepository->findAll(),
            fn(School $school) => $school->getSchoolNumber()
        );

        $csv = Reader::fromString($csvAsString);
        $csv->setHeaderOffset(0);
        $csv->setEscape('');
        $csv->setDelimiter(';');
        $records = $csv->getRecords();

        $stops = [ ];
        $added = 0;
        $updated = 0;

        $this->schoolRepository->beginTransaction();

        foreach($records as $record) {
            $plz = intval($record['PLZ']);
            if($this->importSettings->importPlzStart !== null && $plz < $this->importSettings->importPlzStart) {
                continue;
            }

            if($this->importSettings->importPlzEnd !== null && $plz > $this->importSettings->importPlzEnd) {
                continue;
            }

            $id = $record['SchulNr'];
            $school = $existingSchools[$id] ?? null;

            if($school === null) {
                $school = new School();
                $school->setSchoolNumber($id);
                $added++;
            } else {
                $updated++;
            }

            $school->setName($record['ABez1']);
            $school->setAddress($record['Strasse'] ?? null);
            $school->setCity($record['Ort'] ?? null);

            if(is_numeric($record['PLZ'])) {
                $school->setPlz(intval($record['PLZ']));
            } else {
                $school->setPlz(null);
            }

            $this->schoolRepository->persist($school);
        }

        $this->schoolRepository->commit();
        return new ImportResult($added, $updated, 0);
    }

    private function downloadCsv(): string {
        $response = $this->client->request('GET', $this->importSettings->schoolsImportUrl);

        if($response->getStatusCode() !== 200) {
            throw new RequestFailedException($response->getContent(), 'Anfrage wurde nicht mit HTTP 200 beantwortet.', $response->getStatusCode());
        }

        return $response->getContent();
    }
}
