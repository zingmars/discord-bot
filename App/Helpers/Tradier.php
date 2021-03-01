<?php

namespace App\Helpers;

use App\Classes\AbstractCommand;
use Database\UserConfig;

class Tradier
{
    public static function getKey(AbstractCommand $command): string
    {
        $user = $command->message->author;
        $entityManager = $command->entityManager;

        $args = [
            'user_id' => $user->id,
            'key' => 'TRADIER_TOKEN',
        ];

        $result = $entityManager->getRepository(UserConfig::class)->findBy($args);

        if (!empty($result)) {
            return $result[0]->getValue();
        }

        return '';
    }
}
