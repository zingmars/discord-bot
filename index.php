<?php

include __DIR__ . '/vendor/autoload.php';

use App\Bot;
use App\Helpers\Env;
use App\Services\CleverbotService;
use Discord\Discord;
use Discord\WebSockets\Intents;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (Env::get('ENABLE_CLEVERBOT') === "True" ) {
    $cleverbotService = new CleverbotService();
} else {
    $cleverbotService = null;
}

$discord = new Discord(
    [
        'token' => Env::get('DISCORD_BOT_TOKEN'),
        'loadAllMembers' => true,
        'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS | Intents::GUILD_PRESENCES
    ]
);

$discord->on(
    'ready',
    function ($discord) use ($cleverbotService) {
        echo "Bot is ready.", PHP_EOL;
        new Bot($discord, $cleverbotService);
    }
);

$discord->run();
