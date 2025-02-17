<?php

namespace NotificationChannels\MicrosoftTeams;

class MicrosoftTeamsAdaptiveCard
{
    
    protected $payload = [];

    protected $webhookUrl = null;

    public function __construct()
    {
        $this->payload = [
            "type" => "message",
            "attachments" => [
                [
                    "contentType" => "application/vnd.microsoft.card.adaptive",
                    "content" => [
                        '$schema' => "http://adaptivecards.io/schemas/adaptive-card.json",
                        "type" => "AdaptiveCard",
                        "version" => "1.5",
                        "body" => [],
                        "actions" => []
                    ]
                ]
            ]
        ];
    }

    public static function create(): self
    {
        return new self();
    }

    public function to(?string $webhookUrl): self
    {
        if (!$webhookUrl) {
            throw new \Exception('Webhook url is required. Tried to send a teams notification without a webhook url.');
        }
        $this->webhookUrl = $webhookUrl;

        return $this;
    }

    public function getWebhookUrl(): string
    {
        return $this->webhookUrl;
    }

    public function toNotGiven(): bool
    {
        return ! $this->webhookUrl;
    }

    public function cardTitle(string $title): self
    {
        $this->payload['attachments'][0]['content']['body'][0] = [
            'type' => 'TextBlock',
            'wrap' => true,
            'style' => 'heading',
            'text' => $title,
            'weight' => 'bolder',
            'size' => 'large',
        ];

        return $this;
    }

    public function cardContent(array $contentBlocks): self
    {
        foreach ($contentBlocks as $contentBlock) {
            $this->payload['attachments'][0]['content']['body'][] = $contentBlock->toArray();
        }
        

        return $this;
    }

    public function cardActions(array $actions): self
    {
        foreach ($actions as $action) {
            $this->payload['attachments'][0]['content']['actions'][] = $action->toArray();
        }
    
        return $this;
    }

    public function toArray(): array
    {
        return $this->payload;
    }
}
