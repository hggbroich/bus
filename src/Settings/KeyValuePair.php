<?php

namespace App\Settings;

use Stringable;

class KeyValuePair implements Stringable {
    public string $key;
    public string $value;
    public ValueDataType $type;

    public function __toString(): string {
        return sprintf('%s: %s (%s)', $this->key, $this->value, $this->type->value);
    }
}
