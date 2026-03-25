<?php

namespace App\Enums;

enum TaskPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Urgent = 'urgent';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function color(): string
    {
        return match ($this) {
            self::Low => '#5F5E5A',
            self::Medium => '#185FA5',
            self::High => '#BA7517',
            self::Urgent => '#A32D2D',
        };
    }

    public function sortOrder(): int
    {
        return match ($this) {
            self::Urgent => 1,
            self::High => 2,
            self::Medium => 3,
            self::Low => 4,
        };
    }
}
