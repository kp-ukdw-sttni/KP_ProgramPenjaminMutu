<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve STTNI program studi entries (seeded by ProgramStudiSeeder)
        $prodiTeologi    = ProgramStudi::where('nama_prodi', 'S1 Teologi')->first();
        $prodiPak        = ProgramStudi::where('nama_prodi', 'S1 Pendidikan Agama Kristen (PAK)')->first();
        $prodiS2Teologi  = ProgramStudi::where('nama_prodi', 'S2 Teologi')->first();

        // ── Superadmin / Ketua LPM ────────────────────────────────────────────
        $superadmin = User::firstOrCreate(
            ['email' => 'ketua.lpm@sttni.ac.id'],
            [
                'name'     => 'Ketua LPM STTNI',
                'password' => Hash::make('password123'),
            ]
        );
        $superadmin->syncRoles(['superadmin']);

        // ── Auditor Demo Account (Tim AMI) ────────────────────────────────────
        $auditor = User::firstOrCreate(
            ['email' => 'auditor@sttni.ac.id'],
            [
                'name'     => 'Tim AMI STTNI',
                'password' => Hash::make('password123'),
            ]
        );
        $auditor->syncRoles(['auditor']);

        // ── Auditee: Kaprodi S1 Teologi ───────────────────────────────────────
        $auditeeTeologi = User::firstOrCreate(
            ['email' => 'kaprodi.teologi@sttni.ac.id'],
            [
                'name'             => 'Kaprodi Teologi',
                'password'         => Hash::make('password123'),
                'program_studi_id' => $prodiTeologi?->id,
            ]
        );
        $auditeeTeologi->syncRoles(['auditee']);

        // ── Auditee: Kaprodi S1 PAK ───────────────────────────────────────────
        $auditeePak = User::firstOrCreate(
            ['email' => 'kaprodi.pak@sttni.ac.id'],
            [
                'name'             => 'Kaprodi PAK',
                'password'         => Hash::make('password123'),
                'program_studi_id' => $prodiPak?->id,
            ]
        );
        $auditeePak->syncRoles(['auditee']);

        // ── Auditee: Kaprodi S2 Teologi ───────────────────────────────────────
        $auditeeS2 = User::firstOrCreate(
            ['email' => 'kaprodi.s2@sttni.ac.id'],
            [
                'name'             => 'Kaprodi S2 Teologi',
                'password'         => Hash::make('password123'),
                'program_studi_id' => $prodiS2Teologi?->id,
            ]
        );
        $auditeeS2->syncRoles(['auditee']);

        // ── Cross-role Demo: auditor who also belongs to a prodi ─────────────
        // Demonstrates Spatie multi-role support (e.g., a senior lecturer who
        // audits other programs and is also auditee for their own prodi).
        $crossRole = User::firstOrCreate(
            ['email' => 'crossrole@sttni.ac.id'],
            [
                'name'             => 'Dosen Multi-Role (Auditor & Auditee)',
                'password'         => Hash::make('password123'),
                'program_studi_id' => $prodiTeologi?->id,
            ]
        );
        $crossRole->syncRoles(['auditor', 'auditee']);

        $this->command->info('Demo users seeded:');
        $this->command->table(
            ['Email', 'Password', 'Role', 'Prodi'],
            [
                ['ketua.lpm@sttni.ac.id',       'password123', 'superadmin',        '—'],
                ['auditor@sttni.ac.id',          'password123', 'auditor',           '—'],
                ['kaprodi.teologi@sttni.ac.id',  'password123', 'auditee',           'S1 Teologi'],
                ['kaprodi.pak@sttni.ac.id',      'password123', 'auditee',           'S1 PAK'],
                ['kaprodi.s2@sttni.ac.id',       'password123', 'auditee',           'S2 Teologi'],
                ['crossrole@sttni.ac.id',        'password123', 'auditor + auditee', 'S1 Teologi'],
            ]
        );
    }
}
