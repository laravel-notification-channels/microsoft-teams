<?php

namespace NotificationChannels\MicrosoftTeams;

use Exception;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Client\RequestException;
use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

class MicrosoftTeams
{
    /**
     * API HTTP client.
     *
     * @var HttpClient
     */
    protected $httpClient;

    public function __construct(HttpClient $http)
    {
        $this->httpClient = $http;
    }

    /**
     * Send a message to a MicrosoftTeams channel.
     *
     *
     * @throws CouldNotSendNotification
     */
    public function send(string $url, array $data): ?ResponseInterface
    {
        if (! $url) {
            throw CouldNotSendNotification::microsoftTeamsWebhookUrlMissing();
        }

        try {
            $response = $this->httpClient->post($url, $data)->throw();
        } catch (RequestException $exception) {
            throw CouldNotSendNotification::microsoftTeamsRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithMicrosoftTeams($exception);
        }

        return $response->toPsrResponse();
    }
}
