<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ----------------------------------------------------------------
        // Permissions
        // ----------------------------------------------------------------
        $permissions = [
            'manage-users',
            'manage-dokumen',
            'view-dokumen',
            'manage-standar',
            'create-evaluasi',
            'view-evaluasi',
            'create-audit',
            'respond-audit',
            'close-audit',
            'view-dashboard',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ----------------------------------------------------------------
        // Roles
        // ----------------------------------------------------------------

        /** Superadmin / Ketua LPM — full access */
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superadmin->syncPermissions($permissions);

        /** Auditor (Tim AMI) */
        $auditor = Role::firstOrCreate(['name' => 'auditor', 'guard_name' => 'web']);
        $auditor->syncPermissions([
            'view-dokumen',
            'view-evaluasi',
            'create-audit',
            'close-audit',
            'view-dashboard',
        ]);

        /** Auditee (Kaprodi / GKM) */
        $auditee = Role::firstOrCreate(['name' => 'auditee', 'guard_name' => 'web']);
        $auditee->syncPermissions([
            'view-dokumen',
            'create-evaluasi',
            'view-evaluasi',
            'respond-audit',
            'view-dashboard',
        ]);

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
