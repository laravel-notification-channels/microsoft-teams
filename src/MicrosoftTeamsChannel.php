<?php

namespace NotificationChannels\MicrosoftTeams;

use Illuminate\Notifications\Notification;
use Psr\Http\Message\ResponseInterface;

class MicrosoftTeamsChannel
{
    /**
     * @var MicrosoftTeams
     */
    protected $microsoftTeams;

    /**
     * Channel constructor.
     */
    public function __construct(MicrosoftTeams $microsoftTeams)
    {
        $this->microsoftTeams = $microsoftTeams;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @return ResponseInterface|null
     */
    public function send($notifiable, Notification $notification)
    {
        $adaptiveCard = $notification->toMicrosoftTeams($notifiable);

        if (! $adaptiveCard instanceof MicrosoftTeamsAdaptiveCard) {
            return;
        }

        // if the recipient is not defined get it from the notifiable object
        if ($adaptiveCard->toNotGiven()) {
            $to = $notifiable->routeNotificationFor('microsoftTeams', $notification);

            $adaptiveCard->to($to);
        }

        $response = $this->microsoftTeams->send($adaptiveCard->getWebhookUrl(), $adaptiveCard->toArray());

        return $response;
    }
}
