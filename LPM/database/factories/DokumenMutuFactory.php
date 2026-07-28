<?php

namespace Database\Factories;

use App\Enums\KategoriDokumen;
use App\Enums\Semester;
use App\Models\DokumenMutu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DokumenMutu>
 */
class DokumenMutuFactory extends Factory
{
    protected $model = DokumenMutu::class;

    public function definition(): array
    {
        return [
            'kategori' => $this->faker->randomElement(KategoriDokumen::cases()),
            'judul' => 'Dokumen ' . $this->faker->sentence(4),
            'nomor_dokumen' => 'DOC/' . $this->faker->unique()->numberBetween(1000, 9999),
            'file_path' => null,
            'tahun_berlaku' => $this->faker->numberBetween(2020, 2026),
            'semester' => $this->faker->randomElement(Semester::cases()),
            'is_active' => true,
        ];
    }
}
