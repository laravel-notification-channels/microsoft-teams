<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

use BackedEnum;
use NotificationChannels\MicrosoftTeams\Enums\Color;
use NotificationChannels\MicrosoftTeams\Enums\HorizontalAlignment;
use NotificationChannels\MicrosoftTeams\Enums\IconSize;
use NotificationChannels\MicrosoftTeams\Enums\IconStyle;
use NotificationChannels\MicrosoftTeams\Enums\Spacing;

class Icon
{
    protected string $name;

    protected ?Spacing $spacing;

    protected ?bool $separator;

    protected string $type =  'Icon';

    protected ?HorizontalAlignment $horizontalAlignment;

    protected ?IconStyle $style;

    protected ?Color $color;

    protected ?IconSize $size;

    public function setName(string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    public function setColor(Color $color) : self
    {
        $this->color = $color;
        return $this;
    }

    public function setSpacing(Spacing $spacing) : self
    {
        $this->spacing = $spacing;
        return $this;
    }

    public function setSeparator(bool $separator) : self
    {
        $this->separator = $separator;
        return $this;
    }

    public function setSize(IconSize $iconSize) : self
    {
        $this->size = $iconSize;
        return $this;
    }

    public function setStyle(IconStyle $iconStyle) : self
    {
        $this->style = $iconStyle;
        return $this;
    }

    public function setHorizontalAlignment(HorizontalAlignment $horizontalAlignment) : self
    {
        $this->horizontalAlignment = $horizontalAlignment;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray() : array
    {
        $icon = [];
        $properties = get_object_vars($this);
        foreach ($properties as $propertyName => $propertyValue) {

            if ($propertyValue instanceof BackedEnum) {
                $icon[$propertyName] = $propertyValue->value;
                continue;
            }

            $icon[$propertyName] = $propertyValue;
        }

        return $icon;
    }

}