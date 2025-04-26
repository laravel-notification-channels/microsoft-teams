<?php

namespace NotificationChannels\MicrosoftTeams\Tests;

use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\MicrosoftTeams\ContentBlocks\TextBlock;
use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeams;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsAdaptiveCard;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;
use PHPUnit\Framework\TestCase;

/**
 * Class MicrosoftTeamsChannelTest.
 */
class MicrosoftTeamsChannelTest extends TestCase
{
    /** @var Mockery\Mock */
    protected $microsoftTeams;

    public function setUp(): void
    {
        parent::setUp();
        $this->microsoftTeams = Mockery::mock(MicrosoftTeams::class);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
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
                                'color' => null
                            ]
                        ],
                        'actions' => []
                    ]
                ]
            ]
        ];

        $this->microsoftTeams->shouldReceive('send')
            ->with(
                'https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567',
                $payload
            )
            ->once()
            ->andReturn(new Response(200));

        $channel = new MicrosoftTeamsChannel($this->microsoftTeams);

        $response = $channel->send(new TestNotifiable(), new TestNotification());
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_does_not_send_a_notification_if_the_notifiable_does_not_provide_a_microsoft_teams_channel()
    {
        $this->expectException(CouldNotSendNotification::class);

        $channel = new MicrosoftTeamsChannel($this->microsoftTeams);
        $channel->send(new TestNotifiableWithoutRoute(), new TestNotificationNoWebhookUrl());
    }

    /** @test */
    public function it_does_send_a_notification_if_the_notifiable_does_not_provide_a_microsoft_teams_channel_but_the_to_param_is_set()
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
                                'color' => null
                            ]
                        ],
                        'actions' => []
                    ]
                ]
            ]
        ];
        $this->microsoftTeams->shouldReceive('send')
            ->with(
                'https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567',
                $payload
            )
            ->once()
            ->andReturn(new Response(200));

        $channel = new MicrosoftTeamsChannel($this->microsoftTeams);

        $response = $channel->send(new TestNotifiableWithoutRoute(), new TestNotificationWithToParam());
        $this->assertEquals(200, $response->getStatusCode());
    }
}

/**
 * Class TestNotifiable.
 */
class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForMicrosoftTeams(Notification $notification = null)
    {
        return 'https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567';
    }
}

/**
 * Class TestNotifiableWithoutRoute.
 */
class TestNotifiableWithoutRoute
{
    use Notifiable;
}

/**
 * Class TestNotification.
 */
class TestNotification extends Notification
{
    public function toMicrosoftTeams()
    {
        return (new MicrosoftTeamsAdaptiveCard())
            ->title('Title')
            ->content([TextBlock::create()->setText('Text')]);
    }
}

/**
 * Class TestNotificationNoWebhookUrl.
 */
class TestNotificationNoWebhookUrl extends Notification
{
    /**
     * @param $notifiable
     *
     * @return MicrosoftTeamsMessage
     */
    public function toMicrosoftTeams($notifiable): MicrosoftTeamsAdaptiveCard
    {
        return (new MicrosoftTeamsAdaptiveCard())
            ->title('Title')
            ->content([TextBlock::create()->setText('Text')]);
    }
}

/**
 * Class TestNotificationWithToParam.
 */
class TestNotificationWithToParam extends Notification
{
    /**
     * @param $notifiable
     *
     * @return MicrosoftTeamsAdaptiveCard
     */
    public function toMicrosoftTeams($notifiable): MicrosoftTeamsAdaptiveCard
    {
        return (new MicrosoftTeamsAdaptiveCard())
            ->title('Title')
            ->content([TextBlock::create()->setText('Text')])
            ->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
    }
}
