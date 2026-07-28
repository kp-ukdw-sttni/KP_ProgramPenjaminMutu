<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * STTNI does not use a "Fakultas" (Faculty) structure — it is a Sekolah Tinggi
 * (College of Theology), so this seeder intentionally creates NO faculty records.
 *
 * Program Studi records are seeded by ProgramStudiSeeder with fakultas_id = null.
 */
class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        // Intentionally empty — STTNI (Sekolah Tinggi) does not have Fakultas.
        $this->command->info('FakultasSeeder skipped: STTNI uses no Fakultas structure.');
    }
}
