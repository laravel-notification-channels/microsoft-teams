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
    private string $name;

    private ?Spacing $spacing;

    private ?bool $seperator;

    private string $type =  'Icon';

    private ?HorizontalAlignment $horizontalAlignment;

    private ?IconStyle $style;

    private ?Color $color;

    private ?IconSize $size;

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function setColor(Color $color)
    {
        $this->color = $color;
        return $this;
    }

    public function setSpacing(Spacing $spacing)
    {
        $this->spacing = $spacing;
        return $this;
    }

    public function setSeperator(bool $seperator)
    {
        $this->seperator = $seperator;
        return $this;
    }

    public function setSize(IconSize $iconSize)
    {
        $this->size = $iconSize;
        return $this;
    }

    public function setStyle(IconStyle $iconStyle)
    {
        $this->style = $iconStyle;
        return $this;
    }

    public function setHorizontalAlignment(HorizontalAlignment $horizontalAlignment)
    {
        $this->horizontalAlignment = $horizontalAlignment;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray()
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