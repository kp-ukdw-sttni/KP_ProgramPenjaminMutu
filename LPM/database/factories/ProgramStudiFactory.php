<?php

namespace Database\Factories;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgramStudi>
 */
class ProgramStudiFactory extends Factory
{
    protected $model = ProgramStudi::class;

    public function definition(): array
    {
        return [
            'fakultas_id' => null, // Default to null for Sekolah Tinggi
            'nama_prodi' => $this->faker->unique()->randomElement([
                'S1 Teologi',
                'S1 Pendidikan Agama Kristen (PAK)',
                'S2 Teologi',
                'S1 Manajemen',
                'S1 Akuntansi'
            ]) . ' ' . $this->faker->unique()->numberBetween(100, 999),
            'kepala_prodi' => $this->faker->name(),
        ];
    }
}
