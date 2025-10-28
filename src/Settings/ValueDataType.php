<?php

namespace App\Settings;

use Override;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ValueDataType: string implements TranslatableInterface {

    case String = 'string';
    case Integer = 'integer';
    case Float = 'float';

    #[Override]
    public function trans(TranslatorInterface $translator, ?string $locale = null): string {
        return $translator->trans(
            sprintf('key_value_data_types.%s', $this->value),
            domain: 'enums',
            locale: $locale
        );
    }
}
