<?php

namespace App\Models;

use App\Enums\KategoriTemuan;
use App\Enums\StatusAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditMutu extends Model
{
    use HasFactory;

    protected $table = 'audit_mutu';

    protected $fillable = [
        'evaluasi_diri_id',
        'auditor_id',
        'kategori_temuan',
        'deskripsi_temuan',
        'rekomendasi',
        'rencana_tindak_lanjut',
        'status_audit',
    ];

    protected $casts = [
        'kategori_temuan' => KategoriTemuan::class,
        'status_audit'    => StatusAudit::class,
    ];

    // -----------------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------------

    public function evaluasiDiri(): BelongsTo
    {
        return $this->belongsTo(EvaluasiDiri::class, 'evaluasi_diri_id');
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    public function isOpen(): bool
    {
        return $this->status_audit === StatusAudit::Open;
    }

    public function isInProgress(): bool
    {
        return $this->status_audit === StatusAudit::InProgress;
    }

    public function isClosed(): bool
    {
        return $this->status_audit === StatusAudit::Closed;
    }

    // Accessors for blade views compatibility
    public function getKategoriAttribute(): KategoriTemuan
    {
        return $this->kategori_temuan;
    }

    public function getStatusAttribute(): StatusAudit
    {
        return $this->status_audit;
    }
}
