<?php

namespace Tests\Feature;

use App\Enums\Semester;
use App\Enums\StatusEvaluasi;
use App\Models\EvaluasiDiri;
use App\Models\ProgramStudi;
use App\Models\StandarMutu;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluasiDiriTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Test that an Auditee can successfully create an evaluasi_diri record for both 'Ganjil' and 'Genap' semesters.
     */
    public function test_auditee_can_create_evaluasi_for_both_semesters(): void
    {
        $prodi = ProgramStudi::factory()->create();
        $auditee = User::factory()->create(['program_studi_id' => $prodi->id]);
        $auditee->assignRole('auditee');

        $standar = StandarMutu::factory()->create();

        // Create for Ganjil
        $responseGanjil = $this->actingAs($auditee)
            ->post(route('evaluasi-diri.store'), [
                'standar_mutu_id' => $standar->id,
                'program_studi_id' => $prodi->id,
                'tahun_akademik' => '2025/2026',
                'semester' => Semester::Ganjil->value,
                'capaian_aktual' => '80%',
                'deskripsi_ketercapaian' => 'Sangat baik',
            ]);

        $responseGanjil->assertRedirect(route('evaluasi-diri.index'));
        $this->assertDatabaseHas('evaluasi_diri', [
            'program_studi_id' => $prodi->id,
            'semester' => Semester::Ganjil->value,
            'status' => StatusEvaluasi::Draft->value,
        ]);

        // Create for Genap
        $responseGenap = $this->actingAs($auditee)
            ->post(route('evaluasi-diri.store'), [
                'standar_mutu_id' => $standar->id,
                'program_studi_id' => $prodi->id,
                'tahun_akademik' => '2025/2026',
                'semester' => Semester::Genap->value,
                'capaian_aktual' => '85%',
                'deskripsi_ketercapaian' => 'Hampir sempurna',
            ]);

        $responseGenap->assertRedirect(route('evaluasi-diri.index'));
        $this->assertDatabaseHas('evaluasi_diri', [
            'program_studi_id' => $prodi->id,
            'semester' => Semester::Genap->value,
            'status' => StatusEvaluasi::Draft->value,
        ]);
    }

    /**
     * Test validation rules (e.g., failing when an invalid enum value is submitted).
     */
    public function test_evaluasi_validation_fails_on_invalid_semester(): void
    {
        $prodi = ProgramStudi::factory()->create();
        $auditee = User::factory()->create(['program_studi_id' => $prodi->id]);
        $auditee->assignRole('auditee');

        $standar = StandarMutu::factory()->create();

        $response = $this->actingAs($auditee)
            ->post(route('evaluasi-diri.store'), [
                'standar_mutu_id' => $standar->id,
                'program_studi_id' => $prodi->id,
                'tahun_akademik' => '2025/2026',
                'semester' => 'invalid-semester-value', // Invalid enum value
                'capaian_aktual' => '80%',
                'deskripsi_ketercapaian' => 'Sangat baik',
            ]);

        $response->assertSessionHasErrors(['semester']);
    }

    /**
     * Test that the status transitions correctly from draft to submitted.
     */
    public function test_evaluasi_status_transitions_from_draft_to_submitted(): void
    {
        $prodi = ProgramStudi::factory()->create();
        $auditee = User::factory()->create(['program_studi_id' => $prodi->id]);
        $auditee->assignRole('auditee');

        $evaluasi = EvaluasiDiri::factory()->create([
            'program_studi_id' => $prodi->id,
            'status' => StatusEvaluasi::Draft,
        ]);

        $response = $this->actingAs($auditee)
            ->post(route('evaluasi-diri.submit', $evaluasi));

        $response->assertRedirect(route('evaluasi-diri.show', $evaluasi));
        $this->assertEquals(StatusEvaluasi::Submitted, $evaluasi->fresh()->status);
    }
}
