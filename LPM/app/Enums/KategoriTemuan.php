<?php

namespace App\Enums;

enum KategoriTemuan: string
{
    case KTS                = 'KTS';
    case OB                 = 'OB';
    case PeluangPeningkatan = 'Peluang_Peningkatan';

    public function label(): string
    {
        return match ($this) {
            self::KTS                => 'KTS – Ketidaksesuaian',
            self::OB                 => 'OB – Observasi',
            self::PeluangPeningkatan => 'Peluang Peningkatan',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::KTS                => 'bg-red-100 text-red-700',
            self::OB                 => 'bg-yellow-100 text-yellow-700',
            self::PeluangPeningkatan => 'bg-sky-100 text-sky-700',
        };
    }
}
