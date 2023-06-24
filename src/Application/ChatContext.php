<?php

namespace Asapo\DailyHub\Chat\Application;

use Asapo\DailyHub\Chat\Application\Adapter\AIPlatformAdapterInterface;
use Asapo\DailyHub\Chat\Application\Command\ChatCommandInterface;
use Asapo\DailyHub\Chat\Domain\Model\Chat;
use Symfony\Component\Uid\Uuid;

class ChatContext
{
    /**
     * @param ChatCommandInterface $commands
     */
    public function __construct(
        private readonly AIPlatformAdapterInterface $adapter,
        private readonly array $commands = [],
    ) {
    }

    public function createChat(): Chat
    {
        return new Chat(Uuid::v4()->toRfc4122());
    }

    public function addPrompt(Chat $chat, string $prompt): Chat
    {
        $response = $this->adapter->promptWithContext($prompt, $chat->messages, $this->commands);

        return $chat->withResponse($response);
    }
}
