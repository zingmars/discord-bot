<?php

namespace App\Helpers;

use JetBrains\PhpStorm\Pure;

class Retard
{
    private const MESSAGES = [
        'what the fuck are you doing you fucking retard?',
        'daunis esi?',
        'sen sists neesi?',
        'pn kys',
        'pis sūdu',
        'uzēd fēci',
        'go commit self neck rope',
        'sūkā dirsu',
        '【﻿ｌｅａｖｅ　ｔｈｅ　ｗｏｒｌｄ　ｂｅｈｉｎｄ　ｕ  ｃｏｍｍｉｔ　ｎｅｃｋ】 https://www.amazon.de/dp/B01GL2CUF8',
    ];

    #[Pure] public static function getRandomMessage(): string
    {
        return self::MESSAGES[rand(0, count(self::MESSAGES) - 1)];
    }
}
