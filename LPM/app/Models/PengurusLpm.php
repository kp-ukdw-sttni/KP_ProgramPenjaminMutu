<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengurusLpm extends Model
{
    use HasFactory;

    protected $table = 'pengurus_lpm';

    protected $fillable = [
        'jabatan',
        'nama_lengkap',
        'email',
        'foto',
        'is_permanent',
        'urutan',
    ];

    protected $casts = [
        'is_permanent' => 'boolean',
    ];
}
