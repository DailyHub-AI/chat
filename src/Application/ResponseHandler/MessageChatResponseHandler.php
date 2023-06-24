<?php

namespace Asapo\DailyHub\Chat\Application\ResponseHandler;

use Asapo\DailyHub\Chat\Application\Response\MessageChatResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Twig\Environment;

#[AsMessageHandler]
class MessageChatResponseHandler
{
    public function __construct(
        private readonly HubInterface $hub,
        private readonly Environment $twig,
    ) {
    }

    public function __invoke(MessageChatResponse $response): void
    {
        $this->hub->publish(new Update(
            'chat',
            $this->twig->render('organisms/message.stream.html.twig', [
                'type' => 'answer',
                'from' => 'Chat-Bot',
                'date' => new \DateTimeImmutable(),
                'message' => $response,
            ]),
        ));
    }
}
