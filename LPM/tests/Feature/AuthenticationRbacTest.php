<?php

namespace Tests\Feature;

use App\Models\ProgramStudi;
use App\Models\StandarMutu;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationRbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles and permissions
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Test that an Auditee (Kaprodi) CANNOT access User Management or standard creation modules.
     */
    public function test_auditee_cannot_access_user_management_and_standar_creation(): void
    {
        $prodi = ProgramStudi::factory()->create();
        $auditee = User::factory()->create(['program_studi_id' => $prodi->id]);
        $auditee->assignRole('auditee');

        // Accessing User Management
        $this->actingAs($auditee)
            ->get(route('users.index'))
            ->assertStatus(403);

        $this->actingAs($auditee)
            ->get(route('users.create'))
            ->assertStatus(403);

        $this->actingAs($auditee)
            ->post(route('users.store'), [])
            ->assertStatus(403);

        // Accessing Standar Mutu creation
        $this->actingAs($auditee)
            ->get(route('standar-mutu.create'))
            ->assertStatus(403);

        $this->actingAs($auditee)
            ->post(route('standar-mutu.store'), [])
            ->assertStatus(403);
    }

    /**
     * Test that an Auditor CAN view assigned evaluasi_diri but CANNOT create new standar_mutu.
     */
    public function test_auditor_can_view_evaluasi_but_cannot_create_standar_mutu(): void
    {
        $auditor = User::factory()->create();
        $auditor->assignRole('auditor');

        // Auditor can access evaluasi_diri index
        $this->actingAs($auditor)
            ->get(route('evaluasi-diri.index'))
            ->assertStatus(200);

        // Auditor cannot create new standar_mutu
        $this->actingAs($auditor)
            ->get(route('standar-mutu.create'))
            ->assertStatus(403);

        $this->actingAs($auditor)
            ->post(route('standar-mutu.store'), [])
            ->assertStatus(403);
    }

    /**
     * Test that a Superadmin has full CRUD access across all endpoints.
     */
    public function test_superadmin_has_full_crud_access(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        // Can access User Management
        $this->actingAs($superadmin)
            ->get(route('users.index'))
            ->assertStatus(200);

        $this->actingAs($superadmin)
            ->get(route('users.create'))
            ->assertStatus(200);

        // Can access Standar Mutu Management
        $this->actingAs($superadmin)
            ->get(route('standar-mutu.create'))
            ->assertStatus(200);
    }
}
