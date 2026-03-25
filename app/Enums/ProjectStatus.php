<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Active = 'active';
    case Archived = 'archived';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
