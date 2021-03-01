<?php

include __DIR__ . '/vendor/autoload.php';

use App\Fuckboy;
use App\Helpers\Env;
use Discord\Discord;
use Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/Database"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
);

$entityManager = EntityManager::create($conn, $config);

$discord = new Discord(
    [
        'token' => Env::get('DISCORD_BOT_TOKEN'),
        'loadAllMembers' => true,
    ]
);

$discord->on(
    'ready',
    function ($discord) use ($entityManager) {
        echo "Bot is ready.", PHP_EOL;
        new Fuckboy($discord, $entityManager);
    }
);

$discord->run();
?>
