<?php

namespace App\Services;

use App\Helpers\Env;
use Monolog\Logger;

class CleverbotService
{
    private Logger $logger;
    private ?array $pipes;
    private $nodeProcess; // can't type hint because of https://github.com/php/php-src/pull/1631

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;

        // Look dude, the web API gets cucked so often I really can't be arsed to maintain this crap when there's someone
        // else who already does it. And he does it for free :)
        $descriptorSpec = array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w") // stderr
        );

        $this->nodeProcess = proc_open('node Lib/cleverbot.js', $descriptorSpec, $this->pipes);
        if ($this->nodeProcess === false && !is_resource($this->nodeProcess)) {
            die("Could not initialise cleverbot service");
        }

        // Testing
        //fwrite($this->pipes[0], "Hello");
        //$this->logger->info("Initialising cleverbot. Initial response:" . fgets($this->pipes[1]));
    }

    public function __destruct()
    {
        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
        fclose($this->pipes[2]);
        proc_close($this->nodeProcess);
    }

    public function handle($message): false|string
    {
        $content = $message->content;

        // Resolve mentions to actual usernames to avoid feeding junk data to cleverbot
        if (count($message->mentions) > 1) {
            foreach ($message->mentions as $mention) {
                $content = str_replace('<@!'.$mention->id.'>', $mention->username, $content);
            }
        }

        // Resolve custom emoji to their values in order to avoid feeding Cleverbot Discord-specific syntax
        preg_match_all('/<:\w+:[0-9]+>/', $content, $emojis);
        if (count($emojis[0]) > 0) {
            foreach ($emojis as $emoji) {
                preg_match('/<:(.*?):\d*?>/', $emoji[0], $val);
                $content = str_replace($emoji, $val[1], $content);
            }
        }

        // Remove the original bot mention from the string sent to Cleverbot.
        // Only do it if the message actually contains the bot ID (should be the first mention).
        // It might not be present if the message is a reply.
        if (str_contains($content, Env::get('BOT_USER_ID'))) {
            $content = strstr($content," ");
        }

        // Trim the message.
        // We do this after the previous steps just in case we accidentally introduced some whitespace
        // when clearing up the message.
        $content = trim($content);

        $logMessage = 'Processing cleverbot message: "%s"';
        $this->logger->info(sprintf($logMessage, $content));

        return $this->makeRequest($content);
    }

    public function makeRequest($message): false|string
    {
        //TODO:04-06-2021:Ingmars Melkis: Add a check to make sure the pipes are still active.
        // If the js script dies for whatever reason the bot will just freeze (it won't even crash).
        if (fwrite($this->pipes[0], $message)) {
            return fgets($this->pipes[1]);
        }

        return false;
    }
}