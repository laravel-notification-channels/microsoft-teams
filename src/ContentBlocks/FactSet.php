<?php

namespace NotificationChannels\MicrosoftTeams\ContentBlocks;

use BackedEnum;

use NotificationChannels\MicrosoftTeams\Enums\Spacing;

class FactSet
{
    private ?Spacing $spacing;

    private array $facts;

    private ?bool $seperator;

    private string $type =  'FactSet';

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

    public function setFacts(array $facts)
    {
        $this->facts = $facts;
        return $this;
    }

    public static function create(): self
    {
        return new self();
    }    

    public function toArray()
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