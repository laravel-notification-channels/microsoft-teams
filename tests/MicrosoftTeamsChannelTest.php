<?php

namespace NotificationChannels\MicrosoftTeams\Tests;

use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeams;
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
        $this->microsoftTeams->shouldReceive('send')
            ->with(
                'https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567',
                [
                    '@type' => 'MessageCard',
                    '@context' => 'https://schema.org/extensions',
                    'summary' => 'Hello, MicrosoftTeams!',
                    'themeColor' => '#1976D2',
                    'title' => 'Hello, MicrosoftTeams!',
                    'text' => 'This is my content.',
                ],
            )
            ->once()
            ->andReturn(new Response(200));

        $channel = new MicrosoftTeamsChannel($this->microsoftTeams);

        $response = $channel->send(new TestNotifiable, new TestNotification);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_does_not_send_a_notification_if_the_notifiable_does_not_provide_a_microsoft_teams_channel()
    {
        $this->expectException(CouldNotSendNotification::class);

        $channel = new MicrosoftTeamsChannel($this->microsoftTeams);
        $channel->send(new TestNotifiableWithoutRoute, new TestNotificationNoWebhookUrl);
    }

    /** @test */
    public function it_does_send_a_notification_if_the_notifiable_does_not_provide_a_microsoft_teams_channel_but_the_to_param_is_set()
    {
        $this->microsoftTeams->shouldReceive('send')
            ->with(
                'https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567',
                [
                    '@type' => 'MessageCard',
                    '@context' => 'https://schema.org/extensions',
                    'summary' => 'Hello, MicrosoftTeams!',
                    'themeColor' => '#1976D2',
                    'title' => 'Hello, MicrosoftTeams!',
                    'text' => 'This is my content.',
                ],
            )
            ->once()
            ->andReturn(new Response(200));

        $channel = new MicrosoftTeamsChannel($this->microsoftTeams);

        $response = $channel->send(new TestNotifiableWithoutRoute, new TestNotificationWithToParam);
        $this->assertEquals(200, $response->getStatusCode());
    }
}

/**
 * Class TestNotifiable.
 */
class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForMicrosoftTeams()
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
        return (new MicrosoftTeamsMessage)
            ->title('Hello, MicrosoftTeams!')
            ->content('This is my content.');
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
    public function toMicrosoftTeams($notifiable): MicrosoftTeamsMessage
    {
        return (new MicrosoftTeamsMessage)
            ->title('Hello, MicrosoftTeams!')
            ->content('This is my content.');
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
     * @return MicrosoftTeamsMessage
     */
    public function toMicrosoftTeams($notifiable): MicrosoftTeamsMessage
    {
        return (new MicrosoftTeamsMessage)
            ->title('Hello, MicrosoftTeams!')
            ->content('This is my content.')
            ->to('https://outlook.office.com/webhook/abc-01234/IncomingWebhook/def-567');
    }
}
