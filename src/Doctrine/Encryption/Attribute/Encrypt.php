<?php

namespace App\Doctrine\Encryption\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Encrypt {
    public function __construct(
        public string $encryptedPropertyName,
        public string $previewGenerator,
        public string|null $preventEncryptionValuePropertyName = null
    ) {

    }
}
