<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

class TextBlock
{
    /** @var string Property Type */
    protected $type = 'TextBlock';

    /** @var string TextBlock Text */
    protected $text;

    /** @var string Spacing of the TextBlock. (None,Small,Default,Medium,Large,ExtraLarge,Padding) */
    protected $spacing;

    /** @var bool TextBlock Separator. */
    protected $separator;

    /** @var string Horizontal Alignment of TextBlock. (Left,Center,Right) */
    protected $horizontalAlignment;

    /** @var int Maximum number of lines for TextBlock */
    protected $maxLines;

    /** @var string Style of TextBlock. (Default,ColumnHeader,Heading) */
    protected $style;

    /** @var string TextBlock Font Type. (Default,ColumnHeader,Heading) */
    protected $fontType;

    /** @var string TextBlock Font Size. (Small,Default,Medium,Large,ExtraLarge) */
    protected $size;

    /** @var string TextBlock Weight.(Lighter,Default,Bolder) */
    protected $weight;

    /** @var bool TextBlock isSubtle. */
    protected $isSubtle;

    /** @var string TextBlock Text Color.(Default,Dark,Light,Accent,Good,Warning,Attention) */
    protected $color;

    /** @var bool TextBlock text wrap. */
    protected $wrap = true;

    public static function create(): self
    {
        return new self;
    }

    /**
     * Set the text.
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Set the color.
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set the wrap.
     */
    public function setWrap(bool $wrap): self
    {
        $this->wrap = $wrap;

        return $this;
    }

    /**
     * Set the spacing.
     */
    public function setSpacing(string $spacing): self
    {
        $this->spacing = $spacing;

        return $this;
    }

    /**
     * Set the separator.
     */
    public function setSeparator(bool $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Set the font type.
     */
    public function setFontType(string $fontType): self
    {
        $this->fontType = $fontType;

        return $this;
    }

    /**
     * Set the font size.
     */
    public function setSize(string $fontSize): self
    {
        $this->size = $fontSize;

        return $this;
    }

    /**
     * Set the weight.
     */
    public function setWeight(string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Set the is subtle.
     */
    public function setIsSubtle(bool $isSubtle): self
    {
        $this->isSubtle = $isSubtle;

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
     * Set the horizontal alignment.
     */
    public function setHorizontalAlignment(string $horizontalAlignment): self
    {
        $this->horizontalAlignment = $horizontalAlignment;

        return $this;
    }

    /**
     * Set the maximum lines.
     */
    public function setMaximumLines(int $maxLines): self
    {
        $this->maxLines = $maxLines;

        return $this;
    }

    /**
     * Returns TextBlock properties as an array.
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'text' => $this->text,
            'spacing' => $this->spacing,
            'separator' => $this->separator,
            'horizontalAlignment' => $this->horizontalAlignment,
            'maxLines' => $this->maxLines,
            'style' => $this->style,
            'fontType' => $this->fontType,
            'size' => $this->size,
            'weight' => $this->weight,
            'isSubtle' => $this->isSubtle,
            'color' => $this->color,
            'wrap' => $this->wrap,
        ];
    }
}
