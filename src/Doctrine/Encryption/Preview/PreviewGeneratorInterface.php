<?php

namespace App\Doctrine\Encryption\Preview;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.doctrine.encryption.preview')]
interface PreviewGeneratorInterface {

    public function generate(string|null $value): string|null;
}
