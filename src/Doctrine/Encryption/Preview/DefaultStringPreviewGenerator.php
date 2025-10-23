<?php

namespace App\Doctrine\Encryption\Preview;

use Override;

class DefaultStringPreviewGenerator implements PreviewGeneratorInterface {

    public function __construct(
        public $numberOfChars = 6,
        public string $char = '*',
        public bool $preserveEmptyValueAsNull = true
    ) { }

    #[Override]
    public function generate(string|null $value): string|null {
        if(empty($value) && $this->preserveEmptyValueAsNull) {
            return null;
        }

        return str_repeat($this->char, $this->numberOfChars);
    }
}
