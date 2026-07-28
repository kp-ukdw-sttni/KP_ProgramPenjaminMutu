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
    ];

    protected $casts = [
        'semester' => Semester::class,
        'status'   => StatusEvaluasi::class,
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

    public function isAudited(): bool
    {
        return $this->status === StatusEvaluasi::Audited;
    }
}
