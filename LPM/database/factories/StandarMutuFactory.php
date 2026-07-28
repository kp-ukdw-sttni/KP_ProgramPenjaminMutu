<?php

namespace Database\Factories;

use App\Models\StandarMutu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StandarMutu>
 */
class StandarMutuFactory extends Factory
{
    protected $model = StandarMutu::class;

    public function definition(): array
    {
        $code = 'STD-' . $this->faker->unique()->numberBetween(100, 999);
        return [
            'kode_standar' => $code,
            'nama_standar' => 'Standar ' . $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph(),
            'indikator_kinerja' => $this->faker->sentence(),
            'target_capaian' => $this->faker->sentence(),
        ];
    }
}
