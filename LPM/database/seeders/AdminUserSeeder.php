<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // -- Superadmin / Ketua LPM ------------------------------------------
        Role::firstOrCreate(['name' => 'superadmin']);

        $superadmin = User::updateOrCreate(
            ['email' => 'lpm@sttni.ac.id'],
            [
                'name'     => 'Ketua LPM',
                'password' => Hash::make('password123'),
            ]
        );
        $superadmin->assignRole('superadmin');

        $this->command->info('Default Superadmin user seeded.');
    }
}

