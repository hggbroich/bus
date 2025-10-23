<?php

namespace App\Doctrine\Encryption\Encryptors;

interface EncryptorInterface {
    public function encrypt(?string $value): ?string;

    public function decrypt(?string $value, string $otherPrivateKeyBase64): ?string;
}
