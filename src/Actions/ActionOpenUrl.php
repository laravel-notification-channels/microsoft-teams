<?php

namespace NotificationChannels\MicrosoftTeams\Actions;

class ActionOpenUrl
{
    /** @var string Property Type */
    protected $type = 'Action.OpenUrl';

    /** @var string Title of the Action */
    protected $title;

    /** @var string $mode Mode of the Action. (Primary, Secondary) */
    protected $mode;

    /** @var string $mode Style of the Action Button. (Positive, Destructive) */
    protected $style;

    /** @var string $url URL the Action goes to. */
    protected $url;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Set the title.
     *
     * @param string $title
     *
     * @return ActionOpenUrl
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set the mode.
     *
     * @param string $mode
     *
     * @return ActionOpenUrl
     */
    public function setMode(string $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * Set the style.
     *
     * @param string $style
     *
     * @return ActionOpenUrl
     */
    public function setStyle(string $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * Set the url.
     *
     * @param string $url
     *
     * @return ActionOpenUrl
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Returns Action properties as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'mode' => $this->mode,
            'style' => $this->style,
            'url' => $this->url
        ];
    }
}
