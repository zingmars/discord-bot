<?php

namespace App\Helpers;

class Retard
{
    private const MESSAGES = [
        'what the fuck are you doing you fucking retard?',
        'daunis esi?',
        'sen sists neesi?',
        'rakstīt iemācies',
        'pn kys',
        'pis sūdu',
        'uzēd fēci',
        'go commit self neck rope',
        'tāda komanda neeksistē',
    ];

    public static function getRandomMessage(): string
    {
        return self::MESSAGES[rand(0, count(self::MESSAGES) - 1)];
    }
}
