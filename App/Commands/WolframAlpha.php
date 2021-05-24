<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use App\Helpers\Env;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use DOMDocument;
use Exception;

class WolframAlpha extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->arguments)) {
            $reply = 'syntax: %s%s [query]';
            $this->reply(sprintf($reply, Env::Get('COMMAND_PREFIX'), $this->name));
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
        $this->message->react('⌛');

        $query = urlencode(implode(' ', $this->arguments));

        $apiKey = Env::Get('WOLFRAMALPHA_API_KEY');
        if (empty($apiKey)) {
            $this->reply('App ID not set. No Wolfram Alpha for you :(');
            $this->react('❌');
            return;
        }

        $url = 'http://api.wolframalpha.com/v2/query?input=%s&appid=%s';
        $url = sprintf($url, $query, $apiKey);

        $output = Curl::Get($url);

        $dom = new DomDocument();
        $dom->loadXML($output);

        $pods = $dom->getElementsByTagName('pod');
        if ($pods->count() < 2) {
            $this->reply('No results. Try https://www.google.com/search?q=' . $query);
        } else {
            $embed = new Embed($this->discord);
            $embed->setTitle('Wolfram Alpha result:');
            $body = '**Input:** %s ' . PHP_EOL . '**Answer:**:' . PHP_EOL . '%s' . PHP_EOL . PHP_EOL . '**More info:** https://www.wolframalpha.com/input/?i=%s';

            // WolframAlpha API returns XML with results being separated into <pod> elements.
            // First pod is guaranteed to be interpretation.
            $interpretation = $pods->item(0)->childNodes->item(1)->childNodes->item(3)->nodeValue;

            // The format for pods differ. Rather than try to guess the format just extract all text values.
            $result = '';
            foreach(range(1, $pods->count()) as $podNumber) {
                $currNode = $pods->item($podNumber);
                if ($currNode !== null) {
                    $currNode = $currNode->childNodes->item(1)->childNodes;
                    foreach($currNode as $node) {
                        if ($node->nodeName === 'plaintext') {
                            $result .= $node->nodeValue . PHP_EOL;
                            break;
                        }
                    }
                }

                // Truncate the output because we don't want to spam the chat window too much.
                if (strlen($result) >= 500) {
                    $result = substr($result, 0, 500) . '...';
                    break;
                }
            }

            $body = sprintf($body, $interpretation, $result, $query);

            $this->message->deleteReaction(Message::REACT_DELETE_EMOJI, '⌛');

            $embed->setDescription($body);
            $this->reply('', $embed);
            $this->react('☑');
        }
    }
}
