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
        $members = $this->message->channel->guild->members;
        $memberList = [];

        foreach ($members as $member) {
            // ignore bot
            if ($member->user->id === (int)Env::get('BOT_USER_ID')) {
                continue;
            }

            // ignore author
            if ($member->user->id === $this->message->author->id) {
                continue;
            }

            // ignore offline
            if (is_null($member->client_status)) {
                continue;
            }

            $memberList[] = $member->user->id;
        }

        $randomMemberKey = rand(0, count($memberList) - 1);
        return $memberList[$randomMemberKey];
    }
}
