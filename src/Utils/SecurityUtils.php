<?php

namespace App\Utils;

class SecurityUtils {
    public function createRandomString(int $length): string {
        return bin2hex(random_bytes($length / 2));
    }
}
