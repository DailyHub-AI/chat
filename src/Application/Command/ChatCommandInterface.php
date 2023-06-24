<?php

namespace Asapo\DailyHub\Chat\Application\Command;

use Asapo\DailyHub\Chat\Application\Response\CommandChatResponse;

interface ChatCommandInterface
{
    public function getCommand(): string;

    public function getMessage(array $attributes): string;

    public function createResponse(
        string $prompt,
        array $context,
        array $attributes,
    ): CommandChatResponse;
}
