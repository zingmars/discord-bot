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
        '【﻿ｌｅａｖｅ　ｔｈｅ　ｗｏｒｌｄ　ｂｅｈｉｎｄ　ｕ
ｃｏｍｍｉｔ　ｎｅｃｋ】 https://www.amazon.de/100-nat%C3%BCrliche-Seile-Bootfahren-Kletterseil/dp/B01GL2CUF8/ref=sr_1_7?ie=UTF8&qid=1544115457&sr=8-7',
    ];

    #[Pure] public static function getRandomMessage(): string
    {
        return self::MESSAGES[rand(0, count(self::MESSAGES) - 1)];
    }
}
