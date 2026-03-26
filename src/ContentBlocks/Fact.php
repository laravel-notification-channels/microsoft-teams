<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

class Fact
{
    /** @var string Fact Title */
    protected $title;

    /** @var string Fact Value */
    protected $value;

    public static function create(): self
    {
        return new self;
    }

    /**
     * Set the title.
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the value.
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Returns Fact properties as an array.
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'value' => $this->value,
        ];
    }
}
