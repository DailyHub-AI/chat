<?php

namespace Asapo\DailyHub\Chat\Application\Adapter;

use Asapo\DailyHub\Chat\Application\Command\ChatCommandInterface;
use Asapo\DailyHub\Chat\Application\Response\ChatResponseInterface;

interface AIPlatformAdapterInterface
{
    /**
     * @param ChatResponseInterface[] $context
     * @param ChatCommandInterface[] $commands
     */
    public function promptWithContext(string $prompt, array $context, array $commands = []): ChatResponseInterface;

    public function prompt(string $prompt): string;
}
