<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Backlog = 'backlog';
    case InProgress = 'in_progress';
    case Review = 'review';
    case Testing = 'testing';
    case Done = 'done';

    public function label(): string
    {
        return match ($this) {
            self::Backlog => 'Backlog',
            self::InProgress => 'In Progress',
            self::Review => 'Code Review',
            self::Testing => 'QA / Testing',
            self::Done => 'Done',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Backlog => '#888780',
            self::InProgress => '#378ADD',
            self::Review => '#BA7517',
            self::Testing => '#1D9E75',
            self::Done => '#3B6D11',
        };
    }

    public function isTerminal(): bool
    {
        return $this === self::Done;
    }

    /** Status yang valid sebagai transisi dari status ini */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Backlog => [self::InProgress],
            self::InProgress => [self::Backlog, self::Review, self::Testing],
            self::Review => [self::InProgress, self::Testing, self::Done],
            self::Testing => [self::InProgress, self::Review, self::Done],
            self::Done => [self::Backlog],
        };
    }
}
