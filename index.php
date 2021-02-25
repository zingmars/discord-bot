<?php

include __DIR__ . '/vendor/autoload.php';

use App\Fuckboy;
use App\Helpers\Env;
use Discord\DiscordCommandClient;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$discord = new DiscordCommandClient(
    [
        'token' => Env::get('DISCORD_BOT_TOKEN'),
    ]
);

$discord->on(
    'ready',
    function ($discord) {
        echo "Bot is ready.", PHP_EOL;
        new Fuckboy($discord);
    }
);

$discord->run();
?>
