<?php

namespace App\Models;

use App\Enums\KategoriDokumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DokumenMutu extends Model
{
    use HasFactory;

    protected $table = 'dokumen_mutu';

    protected $fillable = [
        'kategori',
        'judul',
        'nomor_dokumen',
        'file_path',
        'tahun_berlaku',
        'is_active',
    ];

    protected $casts = [
        'kategori'    => KategoriDokumen::class,
        'is_active'   => 'boolean',
        'tahun_berlaku' => 'integer',
    ];

    /**
     * Check if a physical file exists in private storage.
     */
    public function hasFile(): bool
    {
        return $this->file_path && Storage::disk('private')->exists($this->file_path);
    }

    /**
     * Return the original filename for display.
     */
    public function originalFileName(): string
    {
        return $this->file_path ? basename($this->file_path) : '—';
    }
}
