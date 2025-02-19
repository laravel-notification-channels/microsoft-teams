<?php

namespace NotificationChannels\MicrosoftTeams\Test;

use NotificationChannels\MicrosoftTeams\Actions\ActionOpenUrl;
use NotificationChannels\MicrosoftTeams\ContentBlocks\Fact;
use NotificationChannels\MicrosoftTeams\ContentBlocks\FactSet;
use NotificationChannels\MicrosoftTeams\ContentBlocks\Icon;
use NotificationChannels\MicrosoftTeams\ContentBlocks\TextBlock;
use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsAdaptiveCard;
use PHPUnit\Framework\TestCase;

/**
 * Class MicrosoftTeamsMessageTest.
 */
class MicrosoftTeamsAdaptiveCardTest extends TestCase
{

    /** @test */
    public function initial_payload_is_created_when_constructed(): void
    {
        $payload = [
            'type' => 'message',
            'attachments' => [
                [
                    'contentType' => 'application/vnd.microsoft.card.adaptive',
                    'content' => [
                        '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                        'type' => 'AdaptiveCard',
                        'version' => '1.5',
                        'body' => [],
                        'actions' => []
                    ]
                ]
            ]
        ];

        $card = new MicrosoftTeamsAdaptiveCard();
        $this->assertEquals($payload, $card->toArray());
    }

    /** @test */
    public function title_can_be_set(): void
    {
        $card = new MicrosoftTeamsAdaptiveCard();
        $card->title('Title');

        $payload = $card->toArray();

        $titleTextBlock = $payload['attachments'][0]['content']['body'][0];

        $this->assertEquals('Title', $titleTextBlock['text']);
        $this->assertEquals('TextBlock', $titleTextBlock['type']);
    }

    /** @test */
    public function it_can_return_the_payload_as_an_array(): void
    {
        $expectedPayload = [
            'type' => 'message',
            'attachments' => [
                [
                    'contentType' => 'application/vnd.microsoft.card.adaptive',
                    'content' => [
                        '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                        'type' => 'AdaptiveCard',
                        'version' => '1.5',
                        'body' => [
                            [
                                'type' => 'TextBlock',
                                'wrap' => true,
                                'style' => 'heading',
                                'text' => 'Title',
                                'weight' => 'bolder',
                                'size' => 'large',
                            ],
                            [
                                'type' => 'TextBlock',
                                'text' => 'Text',
                                'wrap' => true
                            ]
                        ],
                        'actions' => []
                    ]
                ]
            ]
        ];

        $card = new MicrosoftTeamsAdaptiveCard();
        $card->title('Title');
        $card->content([
            TextBlock::create()->setText('Text')
        ]);

        $this->assertEquals($expectedPayload, $card->toArray());
    }

    /** @test */
    public function the_recipients_webhook_url_can_be_set(): void
    {
        $card = new MicrosoftTeamsAdaptiveCard();
        $card->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
        $this->assertEquals('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567', $card->getWebhookUrl());
    }

    /** @test */
    public function it_throws_an_exception_if_the_recipients_webhook_url_is_an_empty_string(): void
    {
        $card = new MicrosoftTeamsAdaptiveCard();
        $this->expectException(CouldNotSendNotification::class);
        $card->to('');
    }

    /** @test */
    public function it_can_show_the_webhook_url(): void
    {
        $card = new MicrosoftTeamsAdaptiveCard();

        $card->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
        $this->assertEquals('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567', $card->getWebhookUrl());
    }

    /** @test */
    public function text_blocks_can_be_set_in_content(): void
    {
        $expectedPayload = [
            'type' => 'message',
            'attachments' => [
                [
                    'contentType' => 'application/vnd.microsoft.card.adaptive',
                    'content' => [
                        '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                        'type' => 'AdaptiveCard',
                        'version' => '1.5',
                        'body' => [
                            [
                                'type' => 'TextBlock',
                                'text' => 'Text',
                                'wrap' => true,
                            ],
                            [
                                'type' => 'TextBlock',
                                'text' => 'Text',
                                'wrap' => true
                            ]
                        ],
                        'actions' => []
                    ]
                ]
            ]
        ];

        $card = new MicrosoftTeamsAdaptiveCard();
        $card->content([
            TextBlock::create()->setText('Text'),
            TextBlock::create()->setText('Text')
        ]);

        $this->assertEquals($expectedPayload, $card->toArray());
    }

    /** @test */
    public function icons_can_be_set_in_content(): void
    {
        $expectedPayload = [
            'type' => 'message',
            'attachments' => [
                [
                    'contentType' => 'application/vnd.microsoft.card.adaptive',
                    'content' => [
                        '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                        'type' => 'AdaptiveCard',
                        'version' => '1.5',
                        'body' => [
                            [
                                'type' => 'Icon',
                                'name' => 'Alert',
                            ],
                            [
                                'type' => 'Icon',
                                'name' => 'Alert',
                            ]
                        ],
                        'actions' => []
                    ]
                ]
            ]
        ];

        $card = new MicrosoftTeamsAdaptiveCard();
        $card->content([
            Icon::create()->setName('Alert'),
            Icon::create()->setName('Alert')
        ]);

        $this->assertEquals($expectedPayload, $card->toArray());
    }

    /** @test */
    public function factsets_can_be_set_in_content(): void
    {
        $expectedPayload = [
            'type' => 'message',
            'attachments' => [
                [
                    'contentType' => 'application/vnd.microsoft.card.adaptive',
                    'content' => [
                        '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                        'type' => 'AdaptiveCard',
                        'version' => '1.5',
                        'body' => [
                            [
                                'type' => 'FactSet',
                                'facts' => [
                                    [
                                        'title' => 'Fact',
                                        'value' => 'Value'
                                    ],
                                    [
                                        'title' => 'Fact',
                                        'value' => 'Value'
                                    ]  
                                ]
                            ],
                        ],
                        'actions' => []
                    ]
                ]
            ]
        ];
 
        $card = new MicrosoftTeamsAdaptiveCard();
        $card->content([
            FactSet::create()->setFacts([
                Fact::create()->setTitle('Fact')->setValue('Value'),
                Fact::create()->setTitle('Fact')->setValue('Value')
            ])
        ]);

        $this->assertEquals($expectedPayload, $card->toArray());
    }


    /** @test */
    public function actions_can_be_set (): void
    {
        $expectedPayload = [
            'type' => 'message',
            'attachments' => [
                [
                    'contentType' => 'application/vnd.microsoft.card.adaptive',
                    'content' => [
                        '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                        'type' => 'AdaptiveCard',
                        'version' => '1.5',
                        'body' => [],
                        'actions' => [
                            [
                                'type' => 'Action.OpenUrl',
                                'title' => 'Visit Website',
                                'url' => 'https://foo.bar'
                            ],
                            [
                                'type' => 'Action.OpenUrl',
                                'title' => 'Visit Website2',
                                'url' => 'https://foo.bar'
                            ]
                        ]
                    ]
                ]
            ]
        ];
 
        $card = new MicrosoftTeamsAdaptiveCard();
        $card->actions([
            ActionOpenUrl::create()->setTitle('Visit Website')->setUrl('https://foo.bar'),
            ActionOpenUrl::create()->setTitle('Visit Website2')->setUrl('https://foo.bar')
        ]);

        $this->assertEquals($expectedPayload, $card->toArray());
    }
}