<?php

namespace Database\Factories;

use App\Enums\KategoriTemuan;
use App\Enums\StatusAudit;
use App\Models\AuditMutu;
use App\Models\EvaluasiDiri;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditMutu>
 */
class AuditMutuFactory extends Factory
{
    protected $model = AuditMutu::class;

    public function definition(): array
    {
        return [
            'evaluasi_diri_id' => EvaluasiDiri::factory(),
            'auditor_id' => User::factory(),
            'kategori_temuan' => $this->faker->randomElement(KategoriTemuan::cases()),
            'deskripsi_temuan' => $this->faker->paragraph(),
            'rekomendasi' => $this->faker->sentence(),
            'rencana_tindak_lanjut' => null,
            'status_audit' => StatusAudit::Open,
        ];
    }
}
