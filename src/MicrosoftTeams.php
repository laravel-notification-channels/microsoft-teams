<?php

namespace NotificationChannels\MicrosoftTeams;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

class MicrosoftTeams
{
    /**
     * API HTTP client.
     *
     * @var Client
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
            $response = $this->httpClient->post($url, [
                'json' => $data,
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::microsoftTeamsRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithMicrosoftTeams($exception);
        }

        return $response;
    }
}
