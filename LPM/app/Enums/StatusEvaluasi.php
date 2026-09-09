<?php

namespace App\Enums;

enum StatusEvaluasi: string
{
    case Draft     = 'draft';
    case Submitted = 'submitted';
    case Revisi    = 'revisi';
    case Audited   = 'audited';

    public function label(): string
    {
        return match ($this) {
            self::Draft     => 'Draft',
            self::Submitted => 'Menunggu Review',
            self::Revisi    => 'Revisi',
            self::Audited   => 'Selesai',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Draft     => 'bg-gray-100 text-gray-600',
            self::Submitted => 'bg-blue-100 text-blue-700',
            self::Revisi    => 'bg-amber-100 text-amber-700',
            self::Audited   => 'bg-green-100 text-green-700',
        };
    }
}
