<?php

namespace NotificationChannels\MicrosoftTeams\Actions;

use BackedEnum;

use NotificationChannels\MicrosoftTeams\Enums\ButtonStyle;
use NotificationChannels\MicrosoftTeams\Enums\Mode;

class ActionOpenUrl
{
    protected string $type = 'Action.OpenUrl';

    protected string $title;

    protected Mode $mode;

    protected ButtonStyle $style;

    protected string $url;

    public function setTitle(string $title) : self
    {
        $this->title = $title;
        return $this;
    }

    public function setMode(Mode $mode) : self
    {
        $this->mode = $mode;
        return $this;
    }

    public function setStyle(ButtonStyle $style) : self
    {
        $this->style = $style;
        return $this;
    }

    public function setUrl(string $url) : self
    {
        $this->url = $url;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray() : array
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