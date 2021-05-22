<?php

include __DIR__ . '/vendor/autoload.php';

use App\Fuckboy;
use App\Helpers\Env;
use App\Services\CleverbotService;
use Discord\Discord;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$cleverbotService = new CleverbotService(Env::get('CLEVERBOT_FILE_HISTORY'), Env::get('CLEVERBOT_FILE_COOKIE'));

$discord = new Discord(
    [
        'token' => Env::get('DISCORD_BOT_TOKEN'),
        'loadAllMembers' => true,
    ]
);

$discord->on(
    'ready',
    function ($discord) use ($cleverbotService) {
        echo "Bot is ready.", PHP_EOL;
        new Fuckboy($discord, $cleverbotService);
    }
);

$discord->run();
