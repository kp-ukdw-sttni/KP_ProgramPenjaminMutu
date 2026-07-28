<?php

namespace Tests\Feature;

use App\Enums\KategoriTemuan;
use App\Enums\StatusAudit;
use App\Enums\StatusEvaluasi;
use App\Models\AuditMutu;
use App\Models\EvaluasiDiri;
use App\Models\ProgramStudi;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditInternalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Test that an Auditor can create an audit_mutu (finding) linked to a submitted evaluasi_diri.
     */
    public function test_auditor_can_create_audit_finding_on_submitted_evaluasi(): void
    {
        $auditor = User::factory()->create();
        $auditor->assignRole('auditor');

        $evaluasi = EvaluasiDiri::factory()->create([
            'status' => StatusEvaluasi::Submitted,
        ]);

        $response = $this->actingAs($auditor)
            ->post(route('audit-internal.temuan.store', $evaluasi), [
                'evaluasi_diri_id' => $evaluasi->id,
                'kategori_temuan' => KategoriTemuan::KTS->value,
                'deskripsi_temuan' => 'Terdapat dokumen yang kurang lengkap untuk standar ini.',
                'rekomendasi' => 'Segera lengkapi dokumen terkait.',
            ]);

        $response->assertRedirect(route('audit-internal.show', $evaluasi));
        $this->assertDatabaseHas('audit_mutu', [
            'evaluasi_diri_id' => $evaluasi->id,
            'auditor_id' => $auditor->id,
            'kategori_temuan' => KategoriTemuan::KTS->value,
            'status_audit' => StatusAudit::Open->value,
        ]);

        // Status of evaluasi_diri should transition to audited
        $this->assertEquals(StatusEvaluasi::Audited, $evaluasi->fresh()->status);
    }

    /**
     * Test that an Auditee can submit a rencana_tindak_lanjut (CAPA) response to the finding.
     */
    public function test_auditee_can_submit_capa_response_to_finding(): void
    {
        $prodi = ProgramStudi::factory()->create();
        $auditee = User::factory()->create(['program_studi_id' => $prodi->id]);
        $auditee->assignRole('auditee');

        $evaluasi = EvaluasiDiri::factory()->create([
            'program_studi_id' => $prodi->id,
            'status' => StatusEvaluasi::Audited,
        ]);

        $finding = AuditMutu::factory()->create([
            'evaluasi_diri_id' => $evaluasi->id,
            'status_audit' => StatusAudit::Open,
        ]);

        $response = $this->actingAs($auditee)
            ->patch(route('audit-internal.respond', $finding), [
                'rencana_tindak_lanjut' => 'Kami akan melengkapi dokumen dalam waktu maksimal satu minggu dari sekarang.',
            ]);

        $response->assertRedirect(route('audit-internal.show', $evaluasi));
        $this->assertEquals(StatusAudit::InProgress, $finding->fresh()->status_audit);
        $this->assertEquals('Kami akan melengkapi dokumen dalam waktu maksimal satu minggu dari sekarang.', $finding->fresh()->rencana_tindak_lanjut);
    }

    /**
     * Test that the Auditor can transition the audit status to closed.
     */
    public function test_auditor_can_close_audit_finding(): void
    {
        $auditor = User::factory()->create();
        $auditor->assignRole('auditor');

        $evaluasi = EvaluasiDiri::factory()->create([
            'status' => StatusEvaluasi::Audited,
        ]);

        $finding = AuditMutu::factory()->create([
            'evaluasi_diri_id' => $evaluasi->id,
            'status_audit' => StatusAudit::InProgress,
            'rencana_tindak_lanjut' => 'Kami akan melengkapi dokumen ini segera.',
        ]);

        $response = $this->actingAs($auditor)
            ->patch(route('audit-internal.close', $finding));

        $response->assertRedirect(route('audit-internal.show', $evaluasi));
        $this->assertEquals(StatusAudit::Closed, $finding->fresh()->status_audit);
    }
}
