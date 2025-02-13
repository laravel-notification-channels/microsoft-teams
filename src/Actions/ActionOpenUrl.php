<?php

namespace NotificationChannels\MicrosoftTeams\Actions;

use BackedEnum;

use NotificationChannels\MicrosoftTeams\Enums\ButtonStyle;
use NotificationChannels\MicrosoftTeams\Enums\Mode;

class ActionOpenUrl
{
    private string $type = 'Action.OpenUrl';

    private string $title;

    private Mode $mode;

    private ButtonStyle $style;

    private string $url;

    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function setMode(Mode $mode)
    {
        $this->mode = $mode;
        return $this;
    }

    public function setStyle(ButtonStyle $style)
    {
        $this->style = $style;
        return $this;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray()
    {
        $actionOpenUrl = [];
        $properties = get_object_vars($this);
        foreach ($properties as $propertyName => $propertyValue) {

            if ($propertyValue instanceof BackedEnum) {
                $actionOpenUrl[$propertyName] = $propertyValue->value;
                continue;
            }

            $actionOpenUrl[$propertyName] = $propertyValue;
        }

        return $actionOpenUrl;
    }
}