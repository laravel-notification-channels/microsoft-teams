<?php

namespace NotificationChannels\MicrosoftTeams;

use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;

class MicrosoftTeamsMessage
{
    /** @var array Params payload. */
    protected $payload = [];

    /** @var string webhook url of recipient. */
    protected $webhookUrl = null;

    /**
     * @param string $content
     *
     * @return self
     */
    public static function create(string $content = ''): self
    {
        return new self($content);
    }

    /**
     * Message constructor.
     *
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->payload['@type'] = 'MessageCard';
        $this->payload['@context'] = 'https://schema.org/extensions';
        $this->payload['summary'] = 'Incoming Notification';
        $this->payload['themeColor'] = $this->generateThemeColourCode('primary');
        $this->content($content);
    }

    /**
     * Set a title.
     *
     * @param string $title - title
     * @param array $params - optional section can be defined (e.g. [$section = '1'].
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function title(string $title, array $params = []): self
    {
        // if section is defined add it to specified section
        if (isset($params['section'])) {
            $sectionId = $params['section'];
            $this->payload['sections'][$sectionId]['title'] = $title;
        } else {
            $this->payload['title'] = $title;
            $this->payload['summary'] = $title;
        }

        return $this;
    }

    /**
     * Set a summary.
     *
     * @param string $summary - summary
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function summary(string $summary): self
    {
        $this->payload['summary'] = $summary;

        return $this;
    }

    /**
     * Add a type which is used as theme color.
     *
     * @param string $type - type of the card
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function type(string $type): self
    {
        $this->payload['themeColor'] = $this->generateThemeColourCode($type);

        return $this;
    }

    /**
     * Notification message (Supports Markdown).
     *
     * @param string $content
     * @param array $params - optional section can be defined (e.g. [$section = '1'].
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function content(string $content, array $params = []): self
    {
        // if section is defined add it to specified section
        if (isset($params['section'])) {
            $sectionId = $params['section'];
            $this->payload['sections'][$sectionId]['text'] = $content;
        } else {
            $this->payload['text'] = $content;
        }

        return $this;
    }

    /**
     * Add an action.
     *
     * @param string $name - name of the action
     * @param string $type - defaults to 'OpenUri' should be one of the following types:
     *  - OpenUri: Opens a URI in a separate browser or app; optionally targets different URIs based on operating systems
     *  - HttpPOST: Sends a POST request to a URL
     *  - ActionCard: Presents one or more input types and associated actions
     *  - InvokeAddInCommand: Opens an Outlook add-in task pane.
     * * @param array $params - optional params (needed for complex types and for section)
     * For more information check out: https://docs.microsoft.com/en-us/outlook/actionable-messages/message-card-reference
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function action(string $name, $type = 'OpenUri', array $params = []): self
    {

        // fill required values for all types
        $newAction = [
            '@type' => $type,
            'name' => $name,
        ];

        // fill additional params (needed for other types than 'OpenUri')
        if (! empty($params)) {
            $newAction = array_merge($newAction, $params);
        }

        // if section is defined add it to specified section
        if (isset($params['section'])) {
            // remove unsued property from newAction array
            unset($newAction['section']);
            $sectionId = $params['section'];
            $this->payload['sections'][$sectionId]['potentialAction'][] = (object) $newAction;
        } else {
            $this->payload['potentialAction'][] = (object) $newAction;
        }

        return $this;
    }

    /**
     * Add a button.
     * Wrapper for a potential action by just providing $text and $url params.
     *
     * @param string $text - label of the button
     * @param string $url - url to forward to
     * @param array $params - optional params (needed for more complex types  and for section)
     * For more information check out: https://docs.microsoft.com/en-us/outlook/actionable-messages/message-card-reference#openuri-action
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function button(string $text, string $url = '', array $params = []): self
    {

        // fill targets that is needed for a button
        $newButton = [
            'targets' => [
                (object) [
                    'os'=> 'default',
                    'uri' => $url,
                ],
            ],
        ];

        // fill additional params (if any)
        if (! empty($params)) {
            $newButton = array_merge($newButton, $params);
        }

        $this->action($text, 'OpenUri', $newButton);

        return $this;
    }

    /**
     * Add a startGroup property which marks the start of a logical group of information (only for sections).
     *
     * @param string|int $sectionId - in which section to put the property, defaults to standard_section
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function addStartGroupToSection($sectionId = 'standard_section'): self
    {
        $this->payload['sections'][$sectionId]['startGroup'] = true;

        return $this;
    }

    /**
     * Add an activity to a section.
     *
     * @param string $activityImage
     * @param string $activityTitle
     * @param string $activitySubtitle
     * @param string $activityText
     * @param string|int $sectionId - in which section to put the property, defaults to standard_section
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function activity(string $activityImage = '', string $activityTitle = '', string $activitySubtitle = '', string $activityText = '', $sectionId = 'standard_section'): self
    {
        $this->payload['sections'][$sectionId]['activityImage'] = $activityImage;
        $this->payload['sections'][$sectionId]['activityTitle'] = $activityTitle;
        $this->payload['sections'][$sectionId]['activitySubtitle'] = $activitySubtitle;
        $this->payload['sections'][$sectionId]['activityText'] = $activityText;

        return $this;
    }

    /**
     * Add a fact to a section (Supports Markdown).
     *
     * @param string $name
     * @param string $value
     * @param string|int $sectionId - in which section to put the property, defaults to standard_section
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function fact(string $name, string $value, $sectionId = 'standard_section'): self
    {
        $newFact = compact('name', 'value');
        $this->payload['sections'][$sectionId]['facts'][] = $newFact;

        return $this;
    }

    /**
     * Add an image to a section.
     *
     * @param string $imageUri - The URL to the image.
     * @param string $title - A short description of the image. Typically, title is displayed in a tooltip as the user hovers their mouse over the image
     * @param string|int $sectionId - in which section to put the property, defaults to standard_section
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function image(string $imageUri, string $title = '', $sectionId = 'standard_section'): self
    {
        $newImage = [
            'image' => $imageUri,
            'title' => $title,
        ];
        $this->payload['sections'][$sectionId]['images'][] = $newImage;

        return $this;
    }

    /**
     * Add a hero image to a section.
     *
     * @param string $imageUri - The URL to the image.
     * @param string $title - A short description of the image. Typically, title is displayed in a tooltip as the user hovers their mouse over the image
     * @param string|int $sectionId - in which section to put the property, defaults to standard_section
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function heroImage(string $imageUri, string $title = '', $sectionId = 'standard_section'): self
    {
        $newImage = [
            'image' => $imageUri,
            'title' => $title,
        ];
        $this->payload['sections'][$sectionId]['heroImage'] = $newImage;

        return $this;
    }

    /**
     * Additional options to pass to message payload object.
     *
     * @param array $options
     * @param string|int $sectionId - optional in which section to put the property
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function options(array $options, $sectionId = null): self
    {
        if ($sectionId) {
            $this->payload['sections'][$sectionId] = array_merge($this->payload['sections'][$sectionId], $options);
        }
        $this->payload = array_merge($this->payload, $options);

        return $this;
    }

    /**
     * Recipient's webhook url.
     *
     * @param $webhookUrl - url of webhook
     *
     * @throws CouldNotSendNotification
     *
     * @return MicrosoftTeamsMessage $this
     */
    public function to(?string $webhookUrl): self
    {
        if (! $webhookUrl) {
            throw CouldNotSendNotification::microsoftTeamsWebhookUrlMissing();
        }
        $this->webhookUrl = $webhookUrl;

        return $this;
    }

    /**
     * Get webhook url.
     *
     * @return string $webhookUrl
     */
    public function getWebhookUrl(): string
    {
        return $this->webhookUrl;
    }

    /**
     * Determine if webhook url is not given.
     *
     * @return bool
     */
    public function toNotGiven(): bool
    {
        return ! $this->webhookUrl;
    }

    /**
     * Get payload value for given key.
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function getPayloadValue(string $key)
    {
        return $this->payload[$key] ?? null;
    }

    /**
     * Generate a colour code use given by name of type, fallback to primary
     * if named color not found the type should be a hex color code.
     *
     * @param string $type
     *
     * @return string
     */
    private function generateThemeColourCode($type = 'primary'): string
    {
        $namedColors = [
            'primary' => '#1976D2',
            'secondary' => '#424242',
            'accent' => '#82B1FF',
            'error' => '#FF5252',
            'info' => '#2196F3',
            'success' => '#4CAF50',
            'warning' => '#FFC107',
        ];

        return $namedColors[$type] ?? $type;
    }

    /**
     * Returns params payload.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
