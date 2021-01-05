<?php

namespace NotificationChannels\MicrosoftTeams;

use Illuminate\Notifications\Notification;

class MicrosoftTeamsChannel
{
    /**
     * @var MicrosoftTeams
     */
    protected $microsoftTeams;

    /**
     * Channel constructor.
     *
     * @param MicrosoftTeams $microsoftTeams
     */
    public function __construct(MicrosoftTeams $microsoftTeams)
    {
        $this->microsoftTeams = $microsoftTeams;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toMicrosoftTeams($notifiable);

        // if the recipient is not defined get it from the notifiable object
        if ($message->toNotGiven()) {
            $to = $notifiable->routeNotificationFor('microsoftTeams', $notification);

            $message->to($to);
        }

        $response = $this->microsoftTeams->send($message->getWebhookUrl(), $message->toArray());

        return $response;
    }
}
