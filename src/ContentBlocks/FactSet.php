<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

class FactSet
{
    /** @var string Property Type */
    protected $type =  'FactSet';

    /** @var string Spacing of the FactSet. (None,Small,Default,Medium,Large,ExtraLarge,Padding) */
    protected $spacing;

    /** @var array Array of Facts. ([Fact::create()]) */
    protected $facts = [];

    /** @var bool FactSet Separator. */
    protected $separator;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self();
    }    
    
    /**
     * Set the spacing.
     *
     * @param string $spacing
     * 
     * @return FactSet
     */
    public function setSpacing(string $spacing) : self
    {
        $this->spacing = $spacing;
        return $this;
    }

    /**
     * Set the separator.
     *
     * @param bool $separator
     * 
     * @return FactSet
     */
    public function setSeparator(bool $separator) : self
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * Set the facts.
     *
     * @param array $facts
     * 
     * @return FactSet
     */
    public function setFacts(array $facts) : self
    {
        $this->facts = $facts;
        return $this;
    }

    /**
     * Returns FactSet properties as an array.
     *
     * @return array
     */
    public function toArray() : array
    {
        $facts = [];
        
        foreach($this->facts as $fact) {
            $facts[] = $fact->toArray();
        }

        return [
            'type' => $this->type,
            'spacing' => $this->spacing,
            'facts' => $facts,
            'seperator' => $this->separator
        ];
    }
}