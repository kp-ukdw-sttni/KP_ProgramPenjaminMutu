<?php

namespace Database\Factories;

use App\Models\Fakultas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fakultas>
 */
class FakultasFactory extends Factory
{
    protected $model = Fakultas::class;

    public function definition(): array
    {
        return [
            'nama_fakultas' => $this->faker->company() . ' Faculty',
            'singkatan' => strtoupper($this->faker->lexify('???')),
        ];
    }
}
