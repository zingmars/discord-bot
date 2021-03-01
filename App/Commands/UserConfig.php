<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;
use Discord\Parts\Embed\Embed;

class UserConfig extends AbstractCommand implements CommandInterface
{
    public const CONFIG_OPTIONS = [
        'TRADIER_TOKEN' => 'Tradier API Token (when set, enables .stonk and .option commands)',
    ];

    public function validate(): bool
    {
        if ($this->isPrivateMessage()) {
            return true;
        }

        $message = '%s command can only be used from within DMs';
        $this->reply(sprintf($message, $this->name));
        $this->react('ℹ️');
        $this->sendDM('what the fuck up');

        if (count($this->arguments)) {
            $this->message->delete();
        }

        return false;
    }

    private function replyWithSyntaxLine(): void
    {
        $this->reply('syntax: .' . $this->name . ' [get/set] [key] [?value]', $this->getAvailableConfigKeysEmbed());
    }

    public function execute(): void
    {
        if (empty($this->arguments)) {
            $this->replyWithSyntaxLine();
            return;
        }

        if (count($this->arguments) <= 1) {
            $this->replyWithSyntaxLine();
            return;
        }

        switch (strtolower($this->arguments[0])) {
            case 'get':
                $this->get();
                break;
            case 'set':
                $this->set();
                break;
            default:
                $this->replyWithSyntaxLine();
                break;
        }
    }

    private function getAvailableConfigKeysEmbed(): Embed
    {
        $embed = new Embed($this->discord);
        $embed->setTitle('Available config keys:');
        $message = '';
        $row = '**%s**: %s';

        foreach (self::CONFIG_OPTIONS as $key => $description) {
            $message .= sprintf($row, $key, $description) . PHP_EOL;
        }

        $embed->setDescription($message);
        return $embed;
    }

    private function get(): void
    {
        if (!isset($this->arguments[1])) {
            $this->replyWithSyntaxLine();
            return;
        }

        $user = $this->message->author;
        $entityManager = $this->entityManager;

        $args = [
            'user_id' => $user->id,
            'key' => $this->arguments[1],
        ];

        $result = $entityManager->getRepository(\Database\UserConfig::class)->findBy($args);

        if (!empty($result)) {
            $value = $result[0]->getValue();
            $message = '**%s**: %s';
            $this->reply(sprintf($message, $this->arguments[1], $value));
            $this->react('✅');
            return;
        }

        $value = $result[0]->getValue();
        $message = '**%s**: NOT SET';
        $this->reply(sprintf($message, $this->arguments[1], $value));
        $this->react('❌');
    }

    private function getId(): bool
    {
        $user = $this->message->author;
        $entityManager = $this->entityManager;

        $args = [
            'user_id' => $user->id,
            'key' => $this->arguments[1],
        ];

        $result = $entityManager->getRepository(\Database\UserConfig::class)->findBy($args);

        if (!empty($result)) {
            return $result[0]->getId();
        }

        return 0;
    }


    private function set()
    {
        if (!isset($this->arguments[1]) || !isset($this->arguments[2])) {
            $this->replyWithSyntaxLine();
            return;
        }

        $key = $this->arguments[1];
        $value = $this->arguments[2];
        $user = $this->message->author;
        $entityManager = $this->entityManager;

        if ($this->getId()) {
            $config = $this->entityManager->find(\Database\UserConfig::class, $this->getId());
            $config->setValue($value);
        } else {
            $config = new \Database\UserConfig();
            $config->setUserId($user->id);
            $config->setKey($key);
            $config->setValue($value);
        }

        $entityManager->persist($config);
        $entityManager->flush();

        $message = '**%s**: %s';
        $this->reply(sprintf($message, $this->arguments[1], $this->arguments[2]));
        $this->react('✅');
    }
}
