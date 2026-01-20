<?php

namespace NotificationChannels\MicrosoftTeams;

use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;

class MicrosoftTeamsAdaptiveCard
{
    /** @var array Params payload. */
    protected $payload = [];

    /** @var string webhook url of recipient. */
    protected $webhookUrl = null;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Adaptive Card constructor.
     */
    public function __construct()
    {
        $this->payload = [
            "type" => "message",
            "attachments" => [
                [
                    "contentType" => "application/vnd.microsoft.card.adaptive",
                    "contentUrl" => null,
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

    /**
     * Set a title.
     *
     * @param string $title - title
     *
     * @return MicrosoftTeamsAdaptiveCard $this
     */
    public function title(string $title): self
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

    /**
     * Recipient's webhook url.
     *
     * @param $webhookUrl - url of webhook
     *
     * @throws CouldNotSendNotification
     *
     * @return MicrosoftTeamsAdaptiveCard $this
     */
    public function to(?string $webhookUrl): self
    {
        if (!$webhookUrl) {
            throw new CouldNotSendNotification('Webhook url is required. Tried to send a teams notification without a webhook url.');
        }
        $this->webhookUrl = $webhookUrl;

        return $this;
    }

    /**
     * Sets the card to take the full width
     *
     * @return MicrosoftTeamsAdaptiveCard $this
     */
    public function fullWidth(): self
    {
        $this->payload['attachments'][0]['content']['msteams']['width'] = 'Full';

        return $this;
    }

    /**
     * Get webhook url.
     *
     * @return string $webhookUrl
     */
    public function getWebhookUrl(): string
    {
        return $this->webhookUrl;
    }

    /**
     * Determine if webhook url is not given.
     *
     * @return bool
     */
    public function toNotGiven(): bool
    {
        return ! $this->webhookUrl;
    }

    /**
     * Adaptive Card Content
     *
     * @param array $contentBlocks - array of content blocks (e.g. [TextBlock::create(),Icon::create()].
     *
     * @return MicrosoftTeamsAdaptiveCard $this
     */
    public function content(array $contentBlocks): self
    {
        foreach ($contentBlocks as $contentBlock) {
            $this->payload['attachments'][0]['content']['body'][] = $contentBlock->toArray();
        }

        return $this;
    }

    /**
     * Adaptive Card Actions
     *
     * @param array $actions - array of actions (e.g. [ActionOpenUrl::create(),ActionOpenUrl::create()].
     *
     * @return MicrosoftTeamsAdaptiveCard $this
     */
    public function actions(array $actions): self
    {
        foreach ($actions as $action) {
            $this->payload['attachments'][0]['content']['actions'][] = $action->toArray();
        }

        return $this;
    }

    /**
     * Returns payload.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
