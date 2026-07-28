<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas';

    protected $fillable = [
        'nama_fakultas',
        'singkatan',
    ];

    public function programStudis(): HasMany
    {
        return $this->hasMany(ProgramStudi::class, 'fakultas_id');
    }
}
