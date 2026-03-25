<?php

namespace App\Enums;

enum ProjectRole: string
{
    case PM = 'pm';
    case Developer = 'developer';
    case QA = 'qa';
    case Client = 'client';

    public function label(): string
    {
        return match ($this) {
            self::PM => 'Project Manager',
            self::Developer => 'Developer',
            self::QA => 'QA Engineer',
            self::Client => 'Client',
        };
    }

    public function canCreateTask(): bool
    {
        return in_array($this, [self::PM, self::Developer]);
    }
}
