<?php

declare(strict_types=1);

namespace Asapo\DailyHub\Chat\Infrastructure\AI\OpenAI;

use Asapo\DailyHub\Chat\Application\Adapter\AIPlatformAdapterInterface;
use Asapo\DailyHub\Chat\Application\Command\ChatCommandInterface;
use Asapo\DailyHub\Chat\Application\Response\ChatResponseInterface;
use Asapo\DailyHub\Chat\Application\Response\MessageChatResponse;
use OpenAI\Client;

class OpenAIPlatformAdapter implements AIPlatformAdapterInterface
{
    private Client $client;

    public function __construct(
        private string $openAiKey,
    ) {
    }

    private function getClient(): Client
    {
        return $this->client = \OpenAI::client($this->openAiKey);
    }

    public function promptWithContext(string $prompt, array $context, array $commands = []): ChatResponseInterface
    {
        $descriptions = \array_map(
            fn (ChatCommandInterface $function) => \json_encode(['command' => $function->getCommand()]),
            $commands,
        );

        $contextLines = \array_map(
            fn (ChatResponseInterface $response) => $response->getPrompt() . ':' . $response,
            $context,
        );

        $messages = [
            ['role' => 'assistant', 'content' => 'no prose, no explanations'],
            ['role' => 'system', 'content' => 'You are a helper bot to help our user to find the best solution. But the system itself has to be able to read the response. So please your result has to a valid JSON object'],
            ['role' => 'system', 'content' => 'Given the following prompt, create a JSON object which extract different types of response from it.'],
            ['role' => 'system', 'content' => 'The type "message" is a normal chat bot response. The response format for this type should be {"type": "message", "answer": "<your answer>"}'],
            ['role' => 'system', 'content' => 'The type "command" is a special response where you can detect following commands. The response format for this type should be {"type": "command", "command": "<command>", "attributes": [<attributes>]} Commands: These are the commands: ' . \implode(', ', $descriptions)],
            ['role' => 'user', 'content' => 'In following context should be given prompt be answered: ' . \implode('; ', $contextLines)],
            ['role' => 'user', 'content' => 'The prompt: ' . $prompt],
        ];

        $client = $this->getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
        ]);

        dump($response->choices[0]->message->content);

        $result = \json_decode($response->choices[0]->message->content, true);
        if ('message' === $result['type']) {
            return new MessageChatResponse($prompt, $result['answer']);
        }

        $command = $commands[$result['command']];

        return $command->createResponse($prompt, $context, $result['attributes']);
    }

    public function prompt(string $prompt): string
    {
        $client = $this->getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                'role' => 'system', 'content' => $prompt,
            ],
        ]);

        dump($response->choices[0]->message->content);

        return $response->choices[0]->message->content;
    }
}
