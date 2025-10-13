<?php

namespace App\Import;

readonly class ImportResult {
    public function __construct(public int $added, public int $updated, public int $removed) { }
}
