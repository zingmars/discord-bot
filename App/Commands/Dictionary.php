<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use App\Helpers\Env;
use Discord\Parts\Embed\Embed;
use Exception;

class Dictionary extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->arguments)) {
            $reply = 'syntax: %s%s [word] [language=en_US|en_GB|hi|es|fr|ja|ru|de|it|ko|pt-BR|ar|tr]';
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
        if (!isset($this->arguments[1])) {
            $code = 'en-US';
        } else {
            $code = $this->arguments[1];
        }

        $url = 'https://api.dictionaryapi.dev/api/v2/entries/%s/%s';
        $url = sprintf($url, $code, urlencode($this->arguments[0]));

        $output = Curl::Get($url);
        $result = json_decode($output);

        if (isset($result->title) || !isset($result[0])) {
            $this->reply("Word not found :(");
            return;
        }

        $embed = new Embed($this->discord);
        $embed->setTitle('Dictionary result:');
        $body = '**Word:** %s' . PHP_EOL . '**Phonetic(s)**: %s' . PHP_EOL . '**Definition(s):**:' . PHP_EOL . '%s';

        $phonetics = "";
        foreach ($result[0]->phonetics as $phonetic) {
            $phonetics .= $phonetic->text . "; ";
        }

        $definitions = "";
        for ($i = 0; $i <= count($result[0]->meanings)-1; $i++) {
            $definitions .= $i+1 . '. (_' . $result[0]->meanings[$i]->partOfSpeech . '_)' . PHP_EOL;

            for ($j = 0; $j <= count($result[0]->meanings[$i]->definitions)-1; $j++) {
                if (isset($result[0]->meanings[$i]->definitions[$j]->definition)) {
                    $definitions .= $i+1 . '.' . $j+1 . ': ' . $result[0]->meanings[$i]->definitions[$j]->definition . PHP_EOL;

                    if (isset($result[0]->meanings[$i]->definitions[$j]->synonyms) && !empty($result[0]->meanings[$i]->definitions[$j]->synonyms)) {
                        $definitions .= '**Synonym(s)**: ';
                        foreach ($result[0]->meanings[$i]->definitions[$j]->synonyms as $synonym) {
                            $definitions .= $synonym . '; ';
                        }
                        $definitions .= PHP_EOL;
                    }

                    if (isset($result[0]->meanings[$i]->definitions[$j]->antonyms) && !empty($result[0]->meanings[$i]->definitions[$j]->antonyms)) {
                        $definitions .= '**Antonyms(s)**: ';
                        foreach ($result[0]->meanings[$i]->definitions[$j]->synonyms as $antonym) {
                            $definitions .= $antonym . '; ';
                        }
                        $definitions .= PHP_EOL;
                    }
                }
            }
            $definitions .= PHP_EOL;
        }

        $body = sprintf($body, $result[0]->word, $phonetics, $definitions);

        $embed->setDescription($body);
        $this->reply('', $embed);
    }
}
