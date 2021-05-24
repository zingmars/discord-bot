Discord bot
===

A slightly modified version of [this](https://github.com/svengjohnson/discord-bot). Re-licensed just because I could.

A lot of the plugins were ported from an older IRC bot [IRCLinkBot or TaiiwoBot](https://github.com/Taiiwo/IRCLinkBot).

### Requirements

* php 8.0
* curl extension (php8.0-curl)
* xml extension (php8.0-xml)
* composer

For cleverbot support you'll also need

* NodeJS
    * The following libraries: superagent md5 (`cd Lib && npm install superagent md5`)

### Setup

Bot:

* Create a new app [here](https://discord.com/developers/applications/) and add a new Bot in the Bot menu.
  * for the 8-ball plugin you'll need to enable the Server members and presence intents, or it will sometimes fail.
* Replace APPID with your application's ID (Found in App's General Information) in the following URL and open this URL in your browser: https://discord.com/oauth2/authorize?client_id=APPID&scope=bot .

Project:

* download [composer.phar](https://getcomposer.org/download/latest-stable/composer.phar) or install it through your distribution's package manager
* `php composer.phar install`
* `cp .env.example .env`
* Copy your access token, owner's id etc into the .env file. Bot user id is the same as your application id.
* (Optional for cleverbot support) `git submodule update --init --recursive` and set `ENABLE_CLEVERBOT` to True in .env.

### Run

#### Locally

First go through the Setup section above.

For development you can use JetBrains' [PhpStorm](https://www.jetbrains.com/phpstorm/features/). This repository includes a run config. Just open it in PhpStorm, and run it using the run/debug menu.

If you want to run it manually, run index.php, i.e. `php8.0 index.php`.

#### Docker
If you want to use docker you'll need:

* docker
* docker-compose

In the project's directory run `docker-compose build` (don't forget to initialise submodules if you want cleverbot). This will create a docker image. You can then run the image manually or use the same docker-compose.yml file if you want. Just be sure to specify an .env file as a volume. 

### Miscellaneous

* You can add hardcoded replies in [App/Listeners/Message.php](./App/Listeners/Message.php).
* To add new commands, create a class that extends AbstractCommand in the [Commands](./App/Commands) folder, then add it to [CommandHelper](./App/Helpers/CommandHelper.php). Just look at other commands for examples.
* You can get OpenWeather API keys [here](https://home.openweathermap.org/api_keys) (account needed, free tier is available, although limited).
* You can get WolframAlpha API keys [here](https://developer.wolframalpha.com/portal/myapps/).
* You can get Shodan API keys [here](https://developer.shodan.io/);
* SPAM_TARGET settings are for a joke, don't mind those.
