<?php

namespace App\Models;

use App\Enums\Semester;
use App\Enums\StatusEvaluasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class EvaluasiDiri extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_diri';

    protected $fillable = [
        'standar_mutu_id',
        'program_studi_id',
        'tahun_akademik',
        'semester',
        'capaian_aktual',
        'deskripsi_ketercapaian',
        'file_bukti_fisik',
        'status',
        'nilai',
    ];

    protected $casts = [
        'semester' => Semester::class,
        'status'   => StatusEvaluasi::class,
        'nilai'    => 'integer',
    ];

    // -----------------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------------

    public function standarMutu(): BelongsTo
    {
        return $this->belongsTo(StandarMutu::class, 'standar_mutu_id');
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    public function auditMutus(): HasMany
    {
        return $this->hasMany(AuditMutu::class, 'evaluasi_diri_id');
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    public function hasFile(): bool
    {
        return $this->file_bukti_fisik && Storage::disk('private')->exists($this->file_bukti_fisik);
    }

    public function isDraft(): bool
    {
        return $this->status === StatusEvaluasi::Draft;
    }

    public function isSubmitted(): bool
    {
        return $this->status === StatusEvaluasi::Submitted;
    }

    public function isRevisi(): bool
    {
        return $this->status === StatusEvaluasi::Revisi;
    }

    public function isAudited(): bool
    {
        return $this->status === StatusEvaluasi::Audited;
    }

    /**
     * Label for nilai (1-4 scoring system).
     */
    public function nilaiLabel(): string
    {
        return match ($this->nilai) {
            1 => '1 — Sangat Kurang',
            2 => '2 — Kurang',
            3 => '3 — Baik',
            4 => '4 — Sangat Baik',
            default => '—',
        };
    }

    /**
     * Badge color for nilai display.
     */
    public function nilaiColor(): string
    {
        return match ($this->nilai) {
            1 => 'bg-red-100 text-red-700',
            2 => 'bg-amber-100 text-amber-700',
            3 => 'bg-blue-100 text-blue-700',
            4 => 'bg-green-100 text-green-700',
            default => 'bg-gray-100 text-gray-500',
        };
    }
}
