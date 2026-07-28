<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

/**
 * Seeds Program Studi specific to STTNI (Sekolah Tinggi Teologi Nazarene Indonesia).
 *
 * STTNI is a "Sekolah Tinggi" (College), not a full university, so it does NOT
 * use the "Fakultas" (Faculty/School) hierarchy. All programs have fakultas_id = null.
 */
class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'nama_prodi'   => 'S1 Teologi',
                'kepala_prodi' => 'Kaprodi Teologi',
                'fakultas_id'  => null,
            ],
            [
                'nama_prodi'   => 'S1 Pendidikan Agama Kristen (PAK)',
                'kepala_prodi' => 'Kaprodi PAK',
                'fakultas_id'  => null,
            ],
            [
                'nama_prodi'   => 'S2 Teologi',
                'kepala_prodi' => 'Kaprodi S2',
                'fakultas_id'  => null,
            ],
        ];

        foreach ($programs as $program) {
            ProgramStudi::firstOrCreate(
                ['nama_prodi' => $program['nama_prodi']],
                $program
            );
        }

        $this->command->info('STTNI Program Studi seeded:');
        $this->command->table(
            ['Nama Prodi', 'Kepala Prodi'],
            array_map(fn ($p) => [$p['nama_prodi'], $p['kepala_prodi']], $programs)
        );
    }
}
