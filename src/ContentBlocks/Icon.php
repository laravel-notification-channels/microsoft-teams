<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

class Icon
{
    /** @var string Property Type */
    protected $type =  'Icon';

    /** @var string Name of the Icon */
    protected $name;

    /** @var string Spacing of the Icon. (None,Small,Default,Medium,Large,ExtraLarge,Padding) */
    protected $spacing;

    /** @var bool Icon Separator. */
    protected $separator;

    /** @var string Horizontal Alignment of Icon. (Left,Center,Right) */
    protected $horizontalAlignment;

    /** @var string Style of Icon. (Regular,Filled) */
    protected $style;

    /** @var string Color of Icon. (Default,Dark,Light,Accent,Good,Warning,Attention) */
    protected $color;

    /** @var string Size of Icon. (xxSmall,xSmall,Small,Standard,Medium,Large,xLarge,xxLarge) */
    protected $size;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self();
    }    

    /**
     * Set the name.
     *
     * @param string $name
     * 
     * @return Icon
     */
    public function setName(string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the color.
     *
     * @param string $color
     * 
     * @return Icon
     */
    public function setColor(string $color) : self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Set the spacing.
     *
     * @param string $spacing
     * 
     * @return Icon
     */
    public function setSpacing(string $spacing) : self
    {
        $this->spacing = $spacing;
        return $this;
    }

    /**
     * Set the seperator.
     *
     * @param bool $seperator
     * 
     * @return Icon
     */
    public function setSeparator(bool $separator) : self
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * Set the size.
     *
     * @param string $size
     * 
     * @return Icon
     */
    public function setSize(string $size) : self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Set the style.
     *
     * @param string $style
     * 
     * @return Icon
     */
    public function setStyle(string $style) : self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * Set the horizontal alignment.
     *
     * @param string $horizontalAlignment
     * 
     * @return Icon
     */
    public function setHorizontalAlignment(string $horizontalAlignment) : self
    {
        $this->horizontalAlignment = $horizontalAlignment;
        return $this;
    }

    /**
     * Returns Icon properties as an array.
     *
     * @return array
     */
    public function toArray() : array
    {
        $icon = [];
        $properties = get_object_vars($this);
        foreach ($properties as $propertyName => $propertyValue) {
            if (!is_null($propertyValue)) {
                $icon[$propertyName] = $propertyValue;
            }
        }

        return $icon;
    }

}