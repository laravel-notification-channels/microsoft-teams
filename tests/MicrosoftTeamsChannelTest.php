<?php

use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\ContentBlocks\TextBlock;
use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeams;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsAdaptiveCard;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;

beforeEach(function () {
    $this->microsoftTeams = Mockery::mock(MicrosoftTeams::class);
});

it('can send a notification', function () {
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

    $this->microsoftTeams->shouldReceive('send')
        ->with(
            'https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567',
            $payload
        )
        ->once()
        ->andReturn(new Response(200));

    $channel = new MicrosoftTeamsChannel($this->microsoftTeams);

    $response = $channel->send(new TestNotifiable, new TestNotification);

    expect($response->getStatusCode())->toEqual(200);
});

it('does not send a notification if the notifiable does not provide a microsoft teams channel', function () {
    $channel = new MicrosoftTeamsChannel($this->microsoftTeams);
    $channel->send(new TestNotifiableWithoutRoute, new TestNotificationNoWebhookUrl);
})->throws(CouldNotSendNotification::class);

it('does send a notification if the notifiable does not provide a microsoft teams channel but the to param is set', function () {
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

    $this->microsoftTeams->shouldReceive('send')
        ->with(
            'https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567',
            $payload
        )
        ->once()
        ->andReturn(new Response(200));

    $channel = new MicrosoftTeamsChannel($this->microsoftTeams);

    $response = $channel->send(new TestNotifiableWithoutRoute, new TestNotificationWithToParam);

    expect($response->getStatusCode())->toEqual(200);
});

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForMicrosoftTeams(?Notification $notification = null)
    {
        return 'https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567';
    }
}

class TestNotifiableWithoutRoute
{
    use Notifiable;
}

class TestNotification extends Notification
{
    public function toMicrosoftTeams()
    {
        return (new MicrosoftTeamsAdaptiveCard)
            ->title('Title')
            ->content([TextBlock::create()->setText('Text')]);
    }
}

class TestNotificationNoWebhookUrl extends Notification
{
    public function toMicrosoftTeams($notifiable): MicrosoftTeamsAdaptiveCard
    {
        return (new MicrosoftTeamsAdaptiveCard)
            ->title('Title')
            ->content([TextBlock::create()->setText('Text')]);
    }
}

class TestNotificationWithToParam extends Notification
{
    public function toMicrosoftTeams($notifiable): MicrosoftTeamsAdaptiveCard
    {
        return (new MicrosoftTeamsAdaptiveCard)
            ->title('Title')
            ->content([TextBlock::create()->setText('Text')])
            ->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
    }
}
