<?php

include __DIR__ . '/vendor/autoload.php';

use App\Bot;
use App\Helpers\Env;
use App\Services\CleverbotService;
use Discord\Discord;
use Discord\WebSockets\Intents;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$logger = new Logger('Logger');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
$file_log = getenv('FILE_LOG', true);
if ($file_log !== false && $file_log !== "false") {
    $logger->pushHandler(new StreamHandler(__DIR__.'/Log/Bot.log', Logger::INFO));
}

if (Env::get('ENABLE_CLEVERBOT') === "True" ) {
    $cleverbotService = new CleverbotService($logger);
} else {
    $cleverbotService = null;
}

$discord = new Discord(
    [
        'token' => Env::get('DISCORD_BOT_TOKEN'),
        'loadAllMembers' => true,
        'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS | Intents::GUILD_PRESENCES,
        'logger' => $logger,
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
