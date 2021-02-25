<?php

namespace App\Helpers;

class Log
{
    public static function console(string $message) {
        echo $message . PHP_EOL;
    }
}
