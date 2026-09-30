<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class, // Must run first
            AdminUserSeeder::class,      // Depends on roles
            FakultasSeeder::class,
            ProgramStudiSeeder::class,
            StandarMutuSeeder::class,
            PengurusLpmSeeder::class,
        ]);
    }
}
