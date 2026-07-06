<?php

namespace App\Export\Order;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;

class CacheManager {

    public const string LastCreatedFilePath = 'order.csv';

    public function __construct(
        #[Autowire(param: 'order_csv_cache_directory')] private readonly string $storageDirectory,
    ) {

    }

    private function getPath(): string {
        return sprintf('%s/%s', $this->storageDirectory, self::LastCreatedFilePath);
    }

    public function fileExists(): bool {
        return file_exists($this->getPath());
    }

    public function getFile(): File {
        return new File($this->getPath());
    }

    public function writeFile(string $csv): void {
        $handle = fopen($this->getPath(), 'w');
        fwrite($handle, $csv);
        fclose($handle);
    }
}
