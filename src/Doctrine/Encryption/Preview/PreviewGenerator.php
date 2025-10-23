<?php

namespace App\Doctrine\Encryption\Preview;

use App\Doctrine\Encryption\Attribute\Encrypt;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class PreviewGenerator {
    public function __construct(
        #[AutowireIterator('app.doctrine.encryption.preview')] private iterable $generators
    ) {

    }

    public function generatePreview(Encrypt $attribute, string|null $value): string|null {
        foreach($this->generators as $generator) {
            if($attribute->previewGenerator === get_class($generator)) {
                return $generator->generate($value);
            }
        }

        throw new GeneratorNotFoundException(message: sprintf('Generator %s not found.', $attribute->previewGenerator));
    }
}
