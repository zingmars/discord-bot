<?php

include __DIR__ . '/vendor/autoload.php';

use App\Fuckboy;
use App\Helpers\Env;
use Discord\Discord;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$discord = new Discord(
    [
        'token' => Env::get('DISCORD_BOT_TOKEN'),
        'loadAllMembers' => true,
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
