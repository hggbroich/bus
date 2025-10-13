<?php

namespace App\UI;

readonly class IbanUtils {

    public function __construct() {

    }

    public function disguiseIban(string|null $iban): string|null {
        if(empty($iban)) {
            return null;
        }

        for($idx = 0; $idx < strlen($iban) - 4; $idx++) {
            $iban[$idx] = '*';
        }

        return $iban;
    }
}
