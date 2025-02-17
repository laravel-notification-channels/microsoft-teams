<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

use BackedEnum;

use NotificationChannels\MicrosoftTeams\Enums\Spacing;

class FactSet
{
    protected ?Spacing $spacing;

    protected array $facts;

    protected ?bool $separator;

    protected string $type =  'FactSet';

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

    public function setFacts(array $facts) : self
    {
        $this->facts = $facts;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray() : array
    {
        $factSet = [];
        $properties = get_object_vars($this);
        foreach ($properties as $propertyName => $propertyValue) {

            if ($propertyValue instanceof BackedEnum) {
                $factSet[$propertyName] = $propertyValue->value;
                continue;
            }

            if($propertyName === 'facts') {
                foreach($this->facts as $fact) {
                    $factSet[$propertyName][] = $fact->toArray();                
                }

                continue;
            }

            $factSet[$propertyName] = $propertyValue;
        }

        return $factSet;
    }
}