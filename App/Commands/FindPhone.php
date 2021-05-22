<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use App\Helpers\Env;
use Discord\Parts\Embed\Embed;
use Exception;

class FindPhone extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->arguments)) {
            $reply = 'syntax: %s%s [number]';
            $this->reply(sprintf($reply, Env::Get('COMMAND_PREFIX'), $this->name));
            $this->react('❌');
            return false;
        }

        if (!str_starts_with($this->arguments[0], '+')) {
            $this->reply('Phone number needs to begin with a country code...');
            $this->react('❌');
            return false;
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $accountId = Env::Get('OPENCNAM_ACCOUNT');
        if (empty($accountId)) {
            $this->reply('API account not set. No phone lookups for you :(');
            $this->react('❌');
            return;
        }

        $apiKey = Env::Get('OPENCNAM_API_KEY');
        if (empty($apiKey)) {
            $this->reply('API key not set. No phone lookups for you :(');
            $this->react('❌');
            return;
        }

        $url = 'https://api.opencnam.com/v2/phone/%s?format=json&account_sid=%s&auth_token=%s';
        $url = sprintf($url, $this->arguments[0], $accountId, $apiKey);

        $output = Curl::Get($url);
        $result = json_decode($output);

        if (isset($result->err)) {
            $this->reply('An error has occurred: ' . $result->err);
            $this->react('❌');
            return;
        }

        $embed = new Embed($this->discord);
        $embed->setTitle('Phone lookup:');

        $body = '**Number:** %s, **Caller ID**: %s' . PHP_EOL
            . '**Created:** %s, **Updated:** %s';
        $body = sprintf($body, $result->number, $result->name, $result->created, $result->updated);

        $embed->setDescription($body);
        $this->reply('', $embed);
    }
}
