<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

class Fact
{
    private string $title;

    private string $value;

    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray()
    {
        $fact = [];
        $properties = get_object_vars($this);
        foreach ($properties as $propertyName => $propertyValue) {
            $fact[$propertyName] = $propertyValue;
        }

        return $fact;
    }
}