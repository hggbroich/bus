<?php

namespace App\Import;

use DateTime;

trait CsvHelperTrait {

    private function getString(?string $input, string $defaultValue = ''): string {
        return $this->getStringOrNull($input) ?? $defaultValue;
    }

    private function getStringOrNull(?string $input): ?string {
        $input = trim($input);

        return empty($input) ? null : $input;
    }

    private function getIntOrNull(?string $input): ?int {
        if(empty($input)) {
            return null;
        }

        if(!is_numeric($input)) {
            return null;
        }

        return intval($input);
    }

    private function getDateTimeOrNull(?string $input): ?DateTime {
        if(empty($input)) {
            return null;
        }

        return $this->getDateTime($input);
    }

    private function getDateTime(string $input): ?DateTime {
        $dateTime = DateTime::createFromFormat('d.m.Y', $input);

        if($dateTime === false) {
            throw new ValueException(sprintf('"%s" ist kein gültiges Datum', $input));
        }

        return $dateTime;
    }
}
