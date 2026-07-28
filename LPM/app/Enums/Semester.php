<?php

namespace App\Enums;

enum Semester: string
{
    case Ganjil = 'Ganjil';
    case Genap  = 'Genap';

    public function label(): string
    {
        return match ($this) {
            self::Ganjil => 'Semester Ganjil',
            self::Genap  => 'Semester Genap',
        };
    }
}
