<?php

namespace App\Import\Stops;

use App\Entity\Stop;
use App\Import\ImportResult;
use App\Import\RequestFailedException;
use App\Repository\StopRepositoryInterface;
use App\Settings\ImportSettings;
use App\Utils\ArrayUtils;
use League\Csv\Reader;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use ZipArchive;

readonly class GtfsStopsImporter {
    public function __construct(private string $tempDir, private ImportSettings $importSettings, private StopRepositoryInterface $stopRepository, private HttpClientInterface $client) {

    }

    public function import(): ImportResult {
        $stopsAsString = $this->downloadZipAndExtractStopsCsv();
        $csv = Reader::createFromString($stopsAsString);
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(',');
        $csv->setEscape('');

        $existingStops = ArrayUtils::createArrayWithKeys(
            $this->stopRepository->findAllExternal(),
            fn(Stop $stop) => $stop->getExternalId()
        );

        $records = $csv->getRecords();

        $added = 0;
        $updated = 0;

        $this->stopRepository->beginTransaction();

        foreach($records as $record) {
            $latitude = floatval($record['stop_lat']);
            $longitude = floatval($record['stop_lon']);

            if(!$this->isInBoundingBox($latitude, $longitude)) {
                continue;
            }

            $id = $record['stop_id'];
            $stop = $existingStops[$id] ?? null;

            if($stop === null) {
                $stop = new Stop();
                $stop->setExternalId($id);
                $added++;
            } else {
                $updated++;
            }

            $stop->setName($record['stop_name']);
            $stop->setLatitude($latitude);
            $stop->setLongitude($longitude);

            $this->stopRepository->persist($stop);
        }

        $this->stopRepository->commit();

        return new ImportResult($added, $updated, 0);
    }

    private function isInBoundingBox(float $latitude, float $longitude): bool {
        if($this->importSettings->minLongitude === null || $this->importSettings->minLongitude === null || $this->importSettings->maxLatitude === null || $this->importSettings->maxLatitude === null) {
            return true;
        }

        return $this->importSettings->minLatitude < $latitude
            && $latitude < $this->importSettings->maxLatitude
            && $this->importSettings->minLongitude < $longitude
            && $longitude < $this->importSettings->maxLongitude;
    }

    private function downloadZipAndExtractStopsCsv(): string {
        $response = $this->client->request('GET', $this->importSettings->stopsImportUrl);

        if($response->getStatusCode() !== 200) {
            throw new RequestFailedException($response->getContent(), 'Anfrage wurde nicht mit HTTP 200 beantwortet.', $response->getStatusCode());
        }

        $tempFile = tempnam($this->tempDir, "gtfs");

        $handle = fopen($tempFile, "wb");
        foreach($this->client->stream($response) as $chunk) {
            fwrite($handle, $chunk->getContent());
        }

        fclose($handle);

        $zip = new ZipArchive();
        $zip->open($tempFile);

        $stops = $zip->getFromName('stops.txt');
        $zip->close();

        return $stops;
    }
}
