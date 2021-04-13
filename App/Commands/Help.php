<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\CommandHelper;
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
        $embed = new Embed($this->discord);
        $embed->setTitle('Available commands:');
        $body = '';
        $row = '**.%s** %s' . PHP_EOL . 'syntax: .%s %s' . PHP_EOL;

        foreach (CommandHelper::COMMANDS_DESCRIPTIONS as $class => $data) {
            $body .= sprintf($row, $data['command'], $data['description'], $data['command'], $data['syntax']) . PHP_EOL;
        }

        $embed->setDescription($body);
        $this->reply('RTFM', $embed);
    }
}
