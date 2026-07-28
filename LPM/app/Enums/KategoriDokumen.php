<?php

namespace App\Enums;

enum KategoriDokumen: string
{
    case Kebijakan = 'kebijakan';
    case Manual    = 'manual';
    case Standar   = 'standar';
    case Formulir  = 'formulir';

    public function label(): string
    {
        return match ($this) {
            self::Kebijakan => 'Kebijakan',
            self::Manual    => 'Manual Mutu',
            self::Standar   => 'Standar',
            self::Formulir  => 'Formulir',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Kebijakan => 'bg-blue-100 text-blue-800',
            self::Manual    => 'bg-purple-100 text-purple-800',
            self::Standar   => 'bg-green-100 text-green-800',
            self::Formulir  => 'bg-amber-100 text-amber-800',
        };
    }
}
