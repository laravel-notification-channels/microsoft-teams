<?php

namespace NotificationChannels\MicrosoftTeams\Actions;

class ActionOpenUrl
{
    /** @var string Property Type */
    protected $type = 'Action.OpenUrl';

    /** @var string Title of the Action */
    protected $title;

    /** @var string Mode of the Action. (Primary, Secondary) */
    protected $mode;

    /** @var string Style of the Action Button. (Positive, Destructive) */
    protected $style;

    /** @var string URL the Action goes to. */
    protected $url;

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
     * Set the mode.
     */
    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Set the style.
     */
    public function setStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Set the url.
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Returns Action properties as an array.
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'mode' => $this->mode,
            'style' => $this->style,
            'url' => $this->url,
        ];
    }
}
