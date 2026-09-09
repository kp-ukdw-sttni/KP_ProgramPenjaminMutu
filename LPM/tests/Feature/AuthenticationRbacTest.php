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
     * Test that a Superadmin has administrative CRUD access.
     */
    public function test_superadmin_has_admin_crud_access(): void
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

    /**
     * Test Superadmin cannot create evaluasi without Auditee role.
     */
    public function test_superadmin_without_auditee_role_cannot_create_evaluasi(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $this->actingAs($superadmin)
            ->get(route('evaluasi-diri.create'))
            ->assertStatus(403);
    }

    /**
     * Test Superadmin cannot create audit finding without Auditor role.
     */
    public function test_superadmin_without_auditor_role_cannot_create_finding(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $evaluasi = \App\Models\EvaluasiDiri::factory()->create([
            'status' => \App\Enums\StatusEvaluasi::Submitted,
        ]);

        $this->actingAs($superadmin)
            ->get(route('audit-internal.temuan.create', $evaluasi))
            ->assertStatus(403);
    }

    /**
     * Test Standar Mutu evaluation count increments when evaluasi is added.
     */
    public function test_standar_mutu_evaluation_count_increments_when_evaluasi_added(): void
    {
        $user = User::factory()->create();
        $user->assignRole('superadmin');
        $standar = StandarMutu::factory()->create();

        $response = $this->actingAs($user)->get(route('standar-mutu.index'));
        $response->assertStatus(200);
        $response->assertSee('0 Evaluasi');

        \App\Models\EvaluasiDiri::factory()->create(['standar_mutu_id' => $standar->id]);

        $response2 = $this->actingAs($user)->get(route('standar-mutu.index'));
        $response2->assertStatus(200);
        $response2->assertSee('1 Evaluasi');
    }
}
