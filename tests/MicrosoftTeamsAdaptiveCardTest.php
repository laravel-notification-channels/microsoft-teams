<?php

use NotificationChannels\MicrosoftTeams\Actions\ActionOpenUrl;
use NotificationChannels\MicrosoftTeams\ContentBlocks\Fact;
use NotificationChannels\MicrosoftTeams\ContentBlocks\FactSet;
use NotificationChannels\MicrosoftTeams\ContentBlocks\Icon;
use NotificationChannels\MicrosoftTeams\ContentBlocks\TextBlock;
use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsAdaptiveCard;

it('creates the initial payload when constructed', function () {
    $payload = [
        'type' => 'message',
        'attachments' => [
            [
                'contentType' => 'application/vnd.microsoft.card.adaptive',
                'contentUrl' => null,
                'content' => [
                    '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                    'type' => 'AdaptiveCard',
                    'version' => '1.5',
                    'body' => [],
                    'actions' => [],
                ],
            ],
        ],
    ];

    $card = new MicrosoftTeamsAdaptiveCard;

    expect($card->toArray())->toEqual($payload);
});

it('can set a title', function () {
    $card = new MicrosoftTeamsAdaptiveCard;
    $card->title('Title');

    $payload = $card->toArray();
    $titleTextBlock = $payload['attachments'][0]['content']['body'][0];

    expect($titleTextBlock['text'])->toEqual('Title')
        ->and($titleTextBlock['type'])->toEqual('TextBlock');
});

it('can return the payload as an array', function () {
    $expectedPayload = [
        'type' => 'message',
        'attachments' => [
            [
                'contentType' => 'application/vnd.microsoft.card.adaptive',
                'contentUrl' => null,
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
                            'wrap' => true,
                            'spacing' => null,
                            'separator' => null,
                            'horizontalAlignment' => null,
                            'maxLines' => null,
                            'style' => null,
                            'fontType' => null,
                            'size' => null,
                            'weight' => null,
                            'isSubtle' => null,
                            'color' => null,
                        ],
                    ],
                    'actions' => [],
                ],
            ],
        ],
    ];

    $card = new MicrosoftTeamsAdaptiveCard;
    $card->title('Title');
    $card->content([
        TextBlock::create()->setText('Text'),
    ]);

    expect($card->toArray())->toEqual($expectedPayload);
});

it('can set the recipients webhook url', function () {
    $card = new MicrosoftTeamsAdaptiveCard;
    $card->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');

    expect($card->getWebhookUrl())->toEqual('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
});

it('throws an exception if the recipients webhook url is an empty string', function () {
    $card = new MicrosoftTeamsAdaptiveCard;
    $card->to('');
})->throws(CouldNotSendNotification::class);

it('can show the webhook url', function () {
    $card = new MicrosoftTeamsAdaptiveCard;
    $card->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');

    expect($card->getWebhookUrl())->toEqual('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
});

it('can set text blocks in content', function () {
    $expectedPayload = [
        'type' => 'message',
        'attachments' => [
            [
                'contentType' => 'application/vnd.microsoft.card.adaptive',
                'contentUrl' => null,
                'content' => [
                    '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                    'type' => 'AdaptiveCard',
                    'version' => '1.5',
                    'body' => [
                        [
                            'type' => 'TextBlock',
                            'text' => 'Text',
                            'wrap' => true,
                            'spacing' => null,
                            'separator' => null,
                            'horizontalAlignment' => null,
                            'maxLines' => null,
                            'style' => null,
                            'fontType' => null,
                            'size' => null,
                            'weight' => null,
                            'isSubtle' => null,
                            'color' => null,
                        ],
                        [
                            'type' => 'TextBlock',
                            'text' => 'Text',
                            'wrap' => true,
                            'spacing' => null,
                            'separator' => null,
                            'horizontalAlignment' => null,
                            'maxLines' => null,
                            'style' => null,
                            'fontType' => null,
                            'size' => null,
                            'weight' => null,
                            'isSubtle' => null,
                            'color' => null,
                        ],
                    ],
                    'actions' => [],
                ],
            ],
        ],
    ];

    $card = new MicrosoftTeamsAdaptiveCard;
    $card->content([
        TextBlock::create()->setText('Text'),
        TextBlock::create()->setText('Text'),
    ]);

    expect($card->toArray())->toEqual($expectedPayload);
});

it('can set icons in content', function () {
    $expectedPayload = [
        'type' => 'message',
        'attachments' => [
            [
                'contentType' => 'application/vnd.microsoft.card.adaptive',
                'contentUrl' => null,
                'content' => [
                    '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                    'type' => 'AdaptiveCard',
                    'version' => '1.5',
                    'body' => [
                        [
                            'type' => 'Icon',
                            'name' => 'Alert',
                            'spacing' => null,
                            'separator' => null,
                            'horizontalAlignment' => null,
                            'style' => null,
                            'color' => null,
                            'size' => null,
                        ],
                        [
                            'type' => 'Icon',
                            'name' => 'Alert',
                            'spacing' => null,
                            'separator' => null,
                            'horizontalAlignment' => null,
                            'style' => null,
                            'color' => null,
                            'size' => null,
                        ],
                    ],
                    'actions' => [],
                ],
            ],
        ],
    ];

    $card = new MicrosoftTeamsAdaptiveCard;
    $card->content([
        Icon::create()->setName('Alert'),
        Icon::create()->setName('Alert'),
    ]);

    expect($card->toArray())->toEqual($expectedPayload);
});

it('can set factsets in content', function () {
    $expectedPayload = [
        'type' => 'message',
        'attachments' => [
            [
                'contentType' => 'application/vnd.microsoft.card.adaptive',
                'contentUrl' => null,
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
                                    'value' => 'Value',
                                ],
                                [
                                    'title' => 'Fact',
                                    'value' => 'Value',
                                ],
                            ],
                            'spacing' => null,
                            'separator' => null,
                        ],
                    ],
                    'actions' => [],
                ],
            ],
        ],
    ];

    $card = new MicrosoftTeamsAdaptiveCard;
    $card->content([
        FactSet::create()->setFacts([
            Fact::create()->setTitle('Fact')->setValue('Value'),
            Fact::create()->setTitle('Fact')->setValue('Value'),
        ]),
    ]);

    expect($card->toArray())->toEqual($expectedPayload);
});

it('can set actions', function () {
    $expectedPayload = [
        'type' => 'message',
        'attachments' => [
            [
                'contentType' => 'application/vnd.microsoft.card.adaptive',
                'contentUrl' => null,
                'content' => [
                    '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                    'type' => 'AdaptiveCard',
                    'version' => '1.5',
                    'body' => [],
                    'actions' => [
                        [
                            'type' => 'Action.OpenUrl',
                            'title' => 'Visit Website',
                            'url' => 'https://foo.bar',
                            'mode' => null,
                            'style' => null,
                        ],
                        [
                            'type' => 'Action.OpenUrl',
                            'title' => 'Visit Website2',
                            'url' => 'https://foo.bar',
                            'mode' => null,
                            'style' => null,
                        ],
                    ],
                ],
            ],
        ],
    ];

    $card = new MicrosoftTeamsAdaptiveCard;
    $card->actions([
        ActionOpenUrl::create()->setTitle('Visit Website')->setUrl('https://foo.bar'),
        ActionOpenUrl::create()->setTitle('Visit Website2')->setUrl('https://foo.bar'),
    ]);

    expect($card->toArray())->toEqual($expectedPayload);
});

it('can set full width', function () {
    $card = new MicrosoftTeamsAdaptiveCard;
    $card->fullWidth();

    $payload = $card->toArray();
    $cardWidth = $payload['attachments'][0]['content']['msteams']['width'];

    expect($cardWidth)->toEqual('Full');
});
