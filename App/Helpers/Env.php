<?php

namespace App\Helpers;

class Env
{
    public static function get(string $key): string
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        return '';
    }
}
