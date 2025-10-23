<?php

namespace App\Entity;

use Override;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Gender: string implements TranslatableInterface {
    case Male = 'm';
    case Female = 'f';
    case Divers = 'd';
    case Other = 'o';

    #[Override]
    public function trans(TranslatorInterface $translator, ?string $locale = null): string {
        return $translator->trans(
            sprintf('gender.%s', $this->value),
            locale: $locale,
            domain: 'enums'
        );
    }
}
