<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;
use Exception;

class EightBall extends AbstractCommand implements CommandInterface
{
    private array $responses = [
        [
            'tu jau zini gang',
            'jā',
            'jā, bet fonā mirgos',
        ],
        [
            'nē',
        ],
        [
            'nez',
            '<:nez:810903050704912414>',
            'jāprasa <@131877167549251584>',
            'jāprasa <@221755442513051649>',
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
            $reply = 'syntax: .%s [message]';
            $this->reply(sprintf($reply, $this->name));
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
        }

        $this->reply($response);
    }

    /**
     * @return string
     */
    private function getRandomMember(): string
    {
        $members = $this->message->channel->guild->members;
        $memberList = [];

        foreach ($members as $member) {
            if ($member->user->id !== '814543511462477834') {
                $memberList[] = $member->user->id;
            }
        }

        $randomMemberKey = rand(0, count($memberList));
        return $memberList[$randomMemberKey];
    }
}
