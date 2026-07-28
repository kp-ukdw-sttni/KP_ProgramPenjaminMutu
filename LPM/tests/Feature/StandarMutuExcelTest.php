<?php

namespace Tests\Feature;

use App\Models\StandarMutu;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class StandarMutuExcelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_superadmin_can_export_standar_mutu(): void
    {
        Excel::fake();

        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $response = $this->actingAs($superadmin)
            ->get(route('standar-mutu.export'));

        $response->assertStatus(200);
        Excel::assertDownloaded('standar-mutu.xlsx');
    }

    public function test_non_superadmin_cannot_export_standar_mutu(): void
    {
        $auditee = User::factory()->create();
        $auditee->assignRole('auditee');

        $response = $this->actingAs($auditee)
            ->get(route('standar-mutu.export'));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_import_standar_mutu(): void
    {
        Excel::fake();

        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $file = UploadedFile::fake()->create('standar-mutu.xlsx', 100);

        $response = $this->actingAs($superadmin)
            ->post(route('standar-mutu.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('standar-mutu.index'));
        $response->assertSessionHas('success', 'Data standar mutu berhasil diimport.');
        
        Excel::assertImported('standar-mutu.xlsx');
    }

    public function test_non_superadmin_cannot_import_standar_mutu(): void
    {
        $auditee = User::factory()->create();
        $auditee->assignRole('auditee');

        $file = UploadedFile::fake()->create('standar-mutu.xlsx', 100);

        $response = $this->actingAs($auditee)
            ->post(route('standar-mutu.import'), [
                'file' => $file,
            ]);

        $response->assertStatus(403);
    }
}
