<?php

namespace Asapo\DailyHub\Chat\Domain\Model;

use Asapo\DailyHub\Chat\Application\Response\ChatResponseInterface;
use Symfony\Component\Uid\Uuid;

final class Chat
{
    public readonly string $id;
    public readonly \DateTimeImmutable $startedAt;

    /**
     * @param ChatResponseInterface[] $messages
     */
    public function __construct(
        ?string $id = null,
        public readonly array $messages = [],
        ?\DateTimeImmutable $startedAt = null,
    ) {
        $this->id = $id ?? Uuid::v4()->toRfc4122();
        $this->startedAt = $startedAt ?? new \DateTimeImmutable();
    }

    public function withResponse(ChatResponseInterface $response): self
    {
        return new self(
            $this->id,
            array_merge($this->messages, [$response]),
            $this->startedAt,
        );
    }
}
