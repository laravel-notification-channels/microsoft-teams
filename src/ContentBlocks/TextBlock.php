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
    protected string $text;

    protected ?Spacing $spacing;

    protected ?bool $separator;

    protected string $type =  'TextBlock';

    protected ?HorizontalAlignment $horizontalAlignment;

    protected ?int $maxLines;

    protected ?BaseStyle $style;

    protected ?FontType $fontType;

    protected ?FontSize $size;

    protected ?Weight $weight;

    protected ?bool $isSubtle;
    
    protected ?Color $color;

    protected bool $wrap = true;

    public function setText(string $text) : self
    {
        $this->text = $text;
        return $this;
    }

    public function setColor(Color $color) : self
    {
        $this->color = $color;
        return $this;
    }

    public function setWrap(bool $wrap) : self
    {
        $this->wrap = $wrap;
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

    public function setFontType(FontType $fontType) : self
    {
        $this->fontType = $fontType;
        return $this;
    }

    public function setSize(FontSize $fontSize) : self
    {
        $this->size = $fontSize;
        return $this;
    }

    public function setWeight(Weight $weight) : self
    {
        $this->weight = $weight;
        return $this;
    }

    public function setIsSubtle(bool $isSubtle) : self
    {
        $this->isSubtle = $isSubtle;
        return $this;
    }

    public function setStyle(BaseStyle $style) : self
    {
        $this->style = $style;
        return $this;
    }

    public function setHorizontalAlignment(HorizontalAlignment $horizontalAlignment) : self
    {
        $this->horizontalAlignment = $horizontalAlignment;
        return $this;
    }

    public function setMaximumLInes(int $maxLines) : self
    {
        $this->maxLines = $maxLines;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray() : array
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