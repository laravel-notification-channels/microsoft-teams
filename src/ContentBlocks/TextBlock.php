<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

use BackedEnum;
use NotificationChannels\MicrosoftTeams\Enums\BaseStyle;
use NotificationChannels\MicrosoftTeams\Enums\Color;
use NotificationChannels\MicrosoftTeams\Enums\FontSize;
use NotificationChannels\MicrosoftTeams\Enums\FontType;
use NotificationChannels\MicrosoftTeams\Enums\HorizontalAlignment;
use NotificationChannels\MicrosoftTeams\Enums\Spacing;
use NotificationChannels\MicrosoftTeams\Enums\Weight;

class TextBlock
{
    private string $text;

    private ?Spacing $spacing;

    private ?bool $seperator;

    private string $type =  'TextBlock';

    private ?HorizontalAlignment $horizontalAlignment;

    private ?int $maxLines;

    private ?BaseStyle $style;

    private ?FontType $fontType;

    private ?FontSize $size;

    private ?Weight $weight;

    private ?bool $isSubtle;
    
    private ?Color $color;

    private bool $wrap = true;

    public function setText(string $text)
    {
        $this->text = $text;
        return $this;
    }

    public function setColor(Color $color)
    {
        $this->color = $color;
        return $this;
    }

    public function setWrap(bool $wrap)
    {
        $this->wrap = $wrap;
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

    public function setFontType(FontType $fontType)
    {
        $this->fontType = $fontType;
        return $this;
    }

    public function setSize(FontSize $fontSize)
    {
        $this->size = $fontSize;
        return $this;
    }

    public function setWeight(Weight $weight)
    {
        $this->weight = $weight;
        return $this;
    }

    public function setIsSubtle(bool $isSubtle)
    {
        $this->isSubtle = $isSubtle;
        return $this;
    }

    public function setStyle(BaseStyle $style)
    {
        $this->style = $style;
        return $this;
    }

    public function setHorizontalAlignment(HorizontalAlignment $horizontalAlignment)
    {
        $this->horizontalAlignment = $horizontalAlignment;
        return $this;
    }

    public function setMaximumLInes(int $maxLines)
    {
        $this->maxLines = $maxLines;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray()
    {
        $textBlock = [];
        $properties = get_object_vars($this);
        foreach ($properties as $propertyName => $propertyValue) {

            if ($propertyValue instanceof BackedEnum) {
                $textBlock[$propertyName] = $propertyValue->value;
                continue;
            }

            $textBlock[$propertyName] = $propertyValue;
        }

        return $textBlock;
    }
}