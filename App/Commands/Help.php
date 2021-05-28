<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\CommandHelper;
use App\Helpers\Env;
use App\Interfaces\CommandInterface;
use Discord\Parts\Embed\Embed;

class Help extends AbstractCommand implements CommandInterface
{
    public function validate(): bool
    {
        return true;
    }

    public function execute(): void
    {
        $prefix = Env::get('COMMAND_PREFIX');

        $embed = new Embed($this->discord);

        if (!isset($this->arguments[0])) {
            $embed->setTitle('Available commands:');
            $body = '';
            $row = '**%s%s** - %s' . PHP_EOL . 'syntax: %s%s %s' . PHP_EOL;

            foreach (CommandHelper::COMMANDS_DESCRIPTIONS as $class => $data) {
                $body .= sprintf($row, $prefix, $data['command'], $data['shortDescription'], $prefix, $data['command'], $data['syntax']) . PHP_EOL;
            }

            $body .= 'Found a bug? Want to see the source code? Available here: https://github.com/zingmars/discord-bot';

            $embed->setDescription($body);
            $this->reply('', $embed);
        } else {
            if (!array_key_exists($this->arguments[0], CommandHelper::COMMANDS)) {
                $this->reply("Could not find command " . $this->arguments[0]);
                return;
            }
            $class = CommandHelper::COMMANDS[$this->arguments[0]];

            $helpEntry = CommandHelper::COMMANDS_DESCRIPTIONS[$class];
            if (!array_key_exists($class, CommandHelper::COMMANDS_DESCRIPTIONS)) {
                $this->reply('No help entry for command ' . $this->arguments[0] . ' found.');
                return;
            }

            $embed->setTitle('The \'' . $helpEntry['command'] . '\' command:');

            $body = '**%s%s** - %s' . PHP_EOL . '**syntax:** %s%s %s' . PHP_EOL;
            $body = sprintf($body, $prefix, $helpEntry['command'], $helpEntry['longDescription'], $prefix, $helpEntry['command'], $helpEntry['syntax']);

            $body .= 'Found a bug? Want to see the source code? Available here: https://github.com/zingmars/discord-bot';

            $embed->setDescription($body);
            $this->reply('', $embed);
        }
    }
}
