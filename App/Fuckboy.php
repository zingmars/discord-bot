<?php

namespace App;

use App\Listeners\Message;
use Discord\Discord;
use Doctrine\ORM\EntityManager;

class Fuckboy
{
    public function __construct(Discord $discord, EntityManager $entityManager)
    {
        $messageListener = function ($message) use ($discord, $entityManager) {
            new Message($discord, $entityManager, $message);
        };

        $discord->on('message', $messageListener);
    }
}
