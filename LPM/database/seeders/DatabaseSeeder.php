<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class, // Must run first — roles before users
            FakultasSeeder::class,       // No-op for STTNI (Sekolah Tinggi, no Fakultas)
            ProgramStudiSeeder::class,   // STTNI-specific: S1 Teologi, S1 PAK, S2 Teologi
            StandarMutuSeeder::class,    // 24 SN-Dikti standards
            AdminUserSeeder::class,      // Demo users (depends on prodi + roles)
        ]);
    }
}
