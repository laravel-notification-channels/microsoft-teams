<?php

namespace NotificationChannels\MicrosoftTeams\Enums;

enum Color : string
{
    case Default = 'Default';
    case Dark = 'Dark';
    case Light = 'Light';
    case Accent = 'Accent';
    case Good = 'Good';
    case Warning = 'Warning';
    case Attention = 'Attention';
}
