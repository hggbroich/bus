<?php

namespace App\Export\Order;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;

class CacheManager {

    public const string LastOrdersFilePath = 'order.csv';
    public const string LastRequestFilePath = 'request.json';

    public function __construct(
        #[Autowire(param: 'order_csv_cache_directory')] private readonly string $storageDirectory,
    ) {

    }

    private function getOrdersCsvPath(): string {
        return sprintf('%s/%s', $this->storageDirectory, self::LastOrdersFilePath);
    }

    public function ordersCsvFileExists(): bool {
        return file_exists($this->getOrdersCsvPath());
    }

    public function getOrdersCsvFile(): File {
        return new File($this->getOrdersCsvPath());
    }

    public function writeOrdersCsvFile(string $csv): void {
        $handle = fopen($this->getOrdersCsvPath(), 'w');
        fwrite($handle, $csv);
        fclose($handle);
    }

    private function getRequestJsonPath(): string {
        return sprintf('%s/%s', $this->storageDirectory, self::LastRequestFilePath);
    }

    public function requestJsonFileExists(): bool {
        return file_exists($this->getRequestJsonPath());
    }

    public function getRequestJsonFile(): string {
        return file_get_contents($this->getRequestJsonPath());
    }

    public function writeRequestJsonFile(string $json): void {
        $handle = fopen($this->getRequestJsonPath(), 'w');
        fwrite($handle, $json);
        fclose($handle);
    }
}
