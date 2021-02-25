<?php

namespace App\Helpers;

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
    ];

    public static function getRandomMessage(): string
    {
        return self::MESSAGES[rand(0, count(self::MESSAGES) - 1)];
    }
}
