<?php

namespace NotificationChannels\MicrosoftTeams\Enums;

enum Spacing : string
{
    case None = 'None';
    case Small = 'Small';
    case Default = 'Default';
    case Medium = 'Medium';
    case Large = 'Large';
    case ExtraLarge = 'ExtraLarge';
    case Padding = 'Padding';
}