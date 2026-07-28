<?php

namespace Database\Factories;

use App\Enums\Semester;
use App\Enums\StatusEvaluasi;
use App\Models\EvaluasiDiri;
use App\Models\ProgramStudi;
use App\Models\StandarMutu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluasiDiri>
 */
class EvaluasiDiriFactory extends Factory
{
    protected $model = EvaluasiDiri::class;

    public function definition(): array
    {
        return [
            'standar_mutu_id' => StandarMutu::factory(),
            'program_studi_id' => ProgramStudi::factory(),
            'tahun_akademik' => '2025/2026',
            'semester' => $this->faker->randomElement(Semester::cases()),
            'capaian_aktual' => $this->faker->sentence(),
            'deskripsi_ketercapaian' => $this->faker->paragraph(),
            'file_bukti_fisik' => null,
            'status' => StatusEvaluasi::Draft,
        ];
    }
}
