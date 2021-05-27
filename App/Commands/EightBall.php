<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use App\Interfaces\CommandInterface;
use Exception;

class EightBall extends AbstractCommand implements CommandInterface
{
    private array $responses = [
        [
            'tu jau zini gang',
            'jā',
        ],
        [
            'nē',
            'no',
        ],
        [
            'nez',
            '##RANDOMMEMBER'
        ]
    ];

    /**
     * @return bool
     * @throws Exception
     */
    public function validate(): bool
    {
        if (empty($this->arguments)) {
            $reply = 'syntax: %s%s [message]';
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
        $responseType = rand(0, count($this->responses) - 1);
        $responseKey = rand(0, count($this->responses[$responseType]) - 1);

        $response = $this->responses[$responseType][$responseKey];

        if ($response === '##RANDOMMEMBER') {
            $response = 'jāprasa <@%s>';

            $this->reply(sprintf($response, $this->getRandomMember()));
        } else {
            $this->reply($response);
        }
    }

    /**
     * @return string
     */
    private function getRandomMember(): string
    {
        //TODO: Figure out a way to restrain this to the current channel, not the whole server
        // DiscordPHP's channel-> members only works for voice channels.
        $members = $this->message->channel->guild->members;

        $memberList = [];
        foreach ($members as $member) {
            // ignore bot
            if ($member->user->id === Env::get('BOT_USER_ID')) {
                continue;
            }

            // ignore author
            if ($member->user->id === $this->message->author->id) {
                continue;
            }

            // ignore offline
            if ($member->status === "offline") {
                continue;
            }

            array_push($memberList, $member->id);
        }

        if (count($memberList) > 0) {
            return $memberList[array_rand($memberList)];
        } else {
            return Env::get('BOT_USER_ID');
        }
    }
}
