<?php

namespace Asapo\DailyHub\Chat\Application\Response;

class MessageChatResponse implements ChatResponseInterface
{
    public function __construct(
        private readonly string $prompt,
        private readonly string $message,
    ) {
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
