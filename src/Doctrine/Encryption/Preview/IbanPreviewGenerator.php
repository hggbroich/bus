<?php

namespace App\Doctrine\Encryption\Preview;

use App\UI\IbanUtils;
use Override;

readonly class IbanPreviewGenerator implements PreviewGeneratorInterface {

    public function __construct(private IbanUtils $ibanUtils) {

    }

    #[Override]
    public function generate(string|null $value): string|null {
        return $this->ibanUtils->disguiseIban($value);
    }
}
