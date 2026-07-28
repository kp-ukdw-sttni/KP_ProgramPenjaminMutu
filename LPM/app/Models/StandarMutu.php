<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StandarMutu extends Model
{
    use HasFactory;

    protected $table = 'standar_mutu';

    protected $fillable = [
        'kode_standar',
        'nama_standar',
        'deskripsi',
        'indikator_kinerja',
        'target_capaian',
    ];

    public function evaluasiDiris(): HasMany
    {
        return $this->hasMany(EvaluasiDiri::class, 'standar_mutu_id');
    }
}
