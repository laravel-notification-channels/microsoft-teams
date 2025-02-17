<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

class Fact
{
    protected string $title;

    protected string $value;

    public function setTitle(string $title) : self
    {
        $this->title = $title;
        return $this;
    }

    public function setValue(string $value) : self
    {
        $this->value = $value;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray() : array
    {
        $fact = [];
        $properties = get_object_vars($this);
        foreach ($properties as $propertyName => $propertyValue) {
            $fact[$propertyName] = $propertyValue;
        }

        return $fact;
    }
}