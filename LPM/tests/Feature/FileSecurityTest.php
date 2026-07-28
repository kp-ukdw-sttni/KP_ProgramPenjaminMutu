<?php

namespace Tests\Feature;

use App\Enums\KategoriDokumen;
use App\Enums\Semester;
use App\Models\DokumenMutu;
use App\Models\EvaluasiDiri;
use App\Models\ProgramStudi;
use App\Models\StandarMutu;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Mock a file upload for bukti_fisik and dokumen_mutu.
     * Assert that the files are stored in the private disk (e.g. storage/app/private).
     */
    public function test_files_are_stored_in_private_disk(): void
    {
        // Fake the private disk
        Storage::fake('private');

        // 1. Test Dokumen Mutu Upload
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $dokumenFile = UploadedFile::fake()->create('pedoman_mutu.pdf', 500); // 500 KB

        $responseDokumen = $this->actingAs($superadmin)
            ->post(route('dokumen-mutu.store'), [
                'kategori' => KategoriDokumen::Manual->value,
                'judul' => 'Pedoman Mutu STTNI',
                'nomor_dokumen' => 'PM/STTNI/2026/01',
                'tahun_berlaku' => 2026,
                'semester' => Semester::Ganjil->value,
                'is_active' => 1,
                'file' => $dokumenFile,
            ]);

        $responseDokumen->assertRedirect(route('dokumen-mutu.index'));

        $latestDokumen = DokumenMutu::latest()->first();
        $this->assertNotNull($latestDokumen->file_path);
        
        // Assert that the file is stored in private storage
        Storage::disk('private')->assertExists($latestDokumen->file_path);

        // 2. Test Evaluasi Diri Bukti Fisik Upload
        $prodi = ProgramStudi::factory()->create();
        $auditee = User::factory()->create(['program_studi_id' => $prodi->id]);
        $auditee->assignRole('auditee');

        $standar = StandarMutu::factory()->create();
        $buktiFile = UploadedFile::fake()->create('bukti_ketercapaian.zip', 2000); // 2 MB

        $responseEvaluasi = $this->actingAs($auditee)
            ->post(route('evaluasi-diri.store'), [
                'standar_mutu_id' => $standar->id,
                'program_studi_id' => $prodi->id,
                'tahun_akademik' => '2025/2026',
                'semester' => Semester::Ganjil->value,
                'capaian_aktual' => '90%',
                'deskripsi_ketercapaian' => 'Sudah terpenuhi seluruhnya.',
                'file_bukti_fisik' => $buktiFile,
            ]);

        $responseEvaluasi->assertRedirect(route('evaluasi-diri.index'));

        $latestEvaluasi = EvaluasiDiri::latest()->first();
        $this->assertNotNull($latestEvaluasi->file_bukti_fisik);

        // Assert that the file is stored in private storage
        Storage::disk('private')->assertExists($latestEvaluasi->file_bukti_fisik);
    }

    /**
     * Assert that unauthenticated users or unauthorized roles receive a 403 Forbidden or 302/401 Unauthorized
     * when attempting to access the file download routes.
     */
    public function test_unauthenticated_and_unauthorized_access_to_downloads_is_restricted(): void
    {
        // Fake private storage and create file records
        Storage::fake('private');

        $dokumen = DokumenMutu::factory()->create([
            'file_path' => 'dokumen_mutu/test_doc.pdf'
        ]);
        Storage::disk('private')->put('dokumen_mutu/test_doc.pdf', 'dummy pdf content');

        $evaluasi = EvaluasiDiri::factory()->create([
            'file_bukti_fisik' => 'bukti_fisik/test_bukti.zip'
        ]);
        Storage::disk('private')->put('bukti_fisik/test_bukti.zip', 'dummy zip content');

        // --- Scenario A: Unauthenticated (guest) user ---
        // Should be redirected to login page (302)
        $this->get(route('dokumen-mutu.download', $dokumen))
            ->assertRedirect(route('login'));

        $this->get(route('evaluasi-diri.download-bukti', $evaluasi))
            ->assertRedirect(route('login'));

        // --- Scenario B: Authenticated but unauthorized role (no permissions) ---
        // Create a user who does not have any roles or permissions
        $unauthorizedUser = User::factory()->create();

        $this->actingAs($unauthorizedUser)
            ->get(route('dokumen-mutu.download', $dokumen))
            ->assertStatus(403);

        $this->actingAs($unauthorizedUser)
            ->get(route('evaluasi-diri.download-bukti', $evaluasi))
            ->assertStatus(403);
    }
}
