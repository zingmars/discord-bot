Discord bot
===

A slightly modified version of [this](https://github.com/svengjohnson/discord-bot). Re-licensed just because I could.

### Requirements

* php 8.0
* composer

### Setup

Bot:

* Create a new app [here](https://discord.com/developers/applications/) and add a new Bot in the Bot menu.
* Replace APPID with your application's ID (Found in App's General Information) in the following URL and open this URL in your browser: https://discord.com/oauth2/authorize?client_id=APPID&scope=bot .

Project:

* download [composer.phar](https://getcomposer.org/download/latest-stable/composer.phar) or install it through your distribution's package manager
* `php composer.phar install`
* `cp .env.example .env`
* Copy your access token, owner's id etc into the .env file. Bot user id is the same as your application id.

### Run

For development you can use JetBrains' [PhpStorm](https://www.jetbrains.com/phpstorm/features/). This repository includes a run config. Just open it in PhpStorm, and run it using the run/debug menu.

If you want to run it manually, run index.php, i.e. `php8.0 index.php`.

If you want to use docker `TODO`...

### Miscellaneous

* You can add hardcoded replies in [App/Listeners/Message.php](./App/Listeners/Message.php).
* To add new commands, create a php file in the [Commands](./App/Commands) folder, then add it to [CommandHelper](./App/Helpers/CommandHelper.php).