<?php

namespace NotificationChannels\MicrosoftTeams\Exceptions;

use Illuminate\Http\Client\RequestException;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when there's a bad request and an error is responded.
     *
     *
     * @return static
     */
    public static function microsoftTeamsRespondedWithAnError(RequestException $exception)
    {
        $statusCode = $exception->response->status();
        $description = $exception->getMessage();

        return new static("Microsoft Teams responded with an error `{$statusCode} - {$description}`");
    }

    /**
     * Thrown when we're unable to communicate with Microsoft Teams.
     *
     *
     * @return static
     */
    public static function couldNotCommunicateWithMicrosoftTeams(\Exception $exception)
    {
        return new static("The communication with Microsoft Teams failed. `{$exception->getMessage()}`");
    }

    /**
     * Thrown when there is no webhook url provided.
     *
     * @return static
     */
    public static function microsoftTeamsWebhookUrlMissing()
    {
        return new static('Microsoft Teams webhook url is missing. Please add it as param over the MicrosoftTeamsMessage::to($url) method or return it in the notifiable model by providing the method Model::routeNotificationForMicrosoftTeams().');
    }
}
