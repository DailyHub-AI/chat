<?php

namespace Asapo\DailyHub\Chat\Application\Response;

interface ChatResponseInterface
{
    public function getPrompt(): string;

    public function __toString(): string;
}
