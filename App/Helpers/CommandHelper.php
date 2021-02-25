<?php

namespace App\Helpers;

class CommandHelper
{
    private const ALIASES = [
        '\\App\\Commands\\Bj' => '\\App\\Commands\\Blackjack',
    ];

    public static function getClassName(string $className): string
    {
        $className = '\\App\\Commands\\'.ucfirst($className);

        if (isset(self::ALIASES[$className])) {
            return self::ALIASES[$className];
        }

        return $className;
    }
}
