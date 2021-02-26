<?php

namespace App\Helpers;

use JetBrains\PhpStorm\Pure;

class Env
{
    #[Pure] public static function get(string $key): string
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        return '';
    }
}
