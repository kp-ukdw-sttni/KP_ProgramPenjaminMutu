<?php

namespace App\Enums;

enum StatusAudit: string
{
    case Open       = 'open';
    case InProgress = 'in_progress';
    case Closed     = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Open       => 'Open',
            self::InProgress => 'In Progress',
            self::Closed     => 'Closed',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Open       => 'bg-red-100 text-red-700',
            self::InProgress => 'bg-amber-100 text-amber-700',
            self::Closed     => 'bg-green-100 text-green-700',
        };
    }
}
