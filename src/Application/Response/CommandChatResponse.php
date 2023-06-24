<?php

namespace Asapo\DailyHub\Chat\Application\Response;

use Asapo\DailyHub\Chat\Application\Command\ChatCommandInterface;

abstract class CommandChatResponse implements ChatResponseInterface
{
    public function __construct(
        private readonly string $prompt,
        private readonly array $context,
        private readonly ChatCommandInterface $command,
        private readonly array $attributes,
    ) {
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function __toString(): string
    {
        return $this->command->getMessage($this->attributes);
    }
}
