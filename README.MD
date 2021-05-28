Discord bot
===

A slightly modified version of [this](https://github.com/svengjohnson/discord-bot). Re-licensed just because I could.

A lot of the plugins were ported from an older IRC bot [IRCLinkBot or TaiiwoBot](https://github.com/Taiiwo/IRCLinkBot).

### Requirements

* php 8.0
  * the following php extensions: php8.0-curl, php8.0-xml, php8.0-mbstring
* composer

For cleverbot support you'll also need

* NodeJS
    * The following libraries: superagent md5 (`cd Lib && npm install superagent md5`)

### Setup

Bot:

* Create a new app [here](https://discord.com/developers/applications/) and add a new Bot in the Bot menu.
* for the 8-ball plugin you'll need to enable the Server members and presence intents, or it will not work. If you don't have those intents enabled you'll need to remove the part that says `| Intents::GUILD_MEMBERS | Intents::GUILD_PRESENCES` from [index.php](index.php) manually. Sorry.
* Replace APPID with your application's ID (Found in App's General Information) in the following URL and open this URL in your browser: https://discord.com/oauth2/authorize?client_id=APPID&scope=bot to make it join your server.

Project:

* download [composer.phar](https://getcomposer.org/download/latest-stable/composer.phar) or install it through your distribution's package manager
* `php composer.phar install`
* `cp .env.example .env`
  * Afterwards open the .env file and change settings you need.
  * See the [Miscellaneous](#Miscellaneous) section at the bottom of the document to find where you can get the other API keys that this bot needs.
* Copy your access token, owner's id etc into the .env file. Bot user id is the same as your application id.
* (Optional for cleverbot support) `git submodule update --init --recursive` and set `ENABLE_CLEVERBOT` to True in .env.

### Run

#### Locally

First go through the Setup section above.

For development, you can use JetBrains' [PhpStorm](https://www.jetbrains.com/phpstorm/features/). This repository includes a run config. Just open it in PhpStorm, and run it using the run/debug menu.

If you want to run it manually, run index.php, i.e. `php8.0 index.php`.

#### Docker

If you want to use docker you'll need:

* docker
* (Optional) docker-compose

To run:

* In the project's directory run `docker-compose build` (don't forget to initialise submodules if you want cleverbot) to build Docker image. Alternatively you can try `docker build -f Docker/Dockerfile .`. This will create a docker image.
* `cp .docker_env.example .docker_env`
  * Afterwards open the .docker_env file and change the settings you need. 
* You can then run the image manually or use the same `docker-compose.yml` using `docker-compose up` (add `-d` to daemonize it). 
* If you're running it manually be sure to specify the .docker_env file and a Data directory as a volume. Optionally you can also specify a Log directory.

### Miscellaneous

* If you have logging enabled in Docker, and you have user namespace isolation on, don't forget to chown and chmod the [Data](./Data) and [Log](./Log) folders.   
* You can add hardcoded replies in [App/Listeners/Message.php](./App/Listeners/Message.php).
* To add new commands, create a class that extends AbstractCommand in the [Commands](./App/Commands) folder, then add it to [CommandHelper](./App/Helpers/CommandHelper.php). Just look at other commands for examples.
* You can get OpenWeather API keys (for <prefix>weather) [here](https://home.openweathermap.org/api_keys) (account needed, free tier is available, although limited).
* You can get WolframAlpha API keys (for <prefix>wa) [here](https://developer.wolframalpha.com/portal/myapps/).
* You can get Shodan API keys (for <prefix>locateip) [here](https://developer.shodan.io/).
* You can get PositionStack API keys (for <prefix>time, <prefix>location) [here](https://positionstack.com/quickstart).
* <prefix>FindPhone requires a OpenCNAM API key. Have fun getting one!
* SPAM_TARGET settings are for a joke (<prefix>spamgl; <prefix>spamcount), don't mind those.
* Logging is done to stdout. If you're using docker-compose, you can use `docker-compose logs` to track the logs.
  * You can also use env variables `CLEVERBOT_HISTORY_LOG` and `FILE_LOG` to log to the [Log](./Log) directory.
* The Cleverbot and Google Translate (<prefix>translate) commands don't use the official APIs, so take care to not abuse them too much, or you risk getting banned.
