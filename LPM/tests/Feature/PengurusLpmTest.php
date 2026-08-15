<?php

namespace Tests\Feature;

use App\Models\PengurusLpm;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengurusLpmTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles and permissions
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Test that any authenticated user can view the kepengurusan list.
     */
    public function test_authenticated_user_can_view_index(): void
    {
        $user = User::factory()->create();
        $user->assignRole('auditee'); // Assign non-admin role

        $this->actingAs($user)
            ->get(route('pengurus-lpm.index'))
            ->assertStatus(200);
    }

    /**
     * Test that unauthorized users cannot access management routes.
     */
    public function test_unauthorized_user_cannot_access_management_routes(): void
    {
        $user = User::factory()->create();
        $user->assignRole('auditee');

        $pengurus = PengurusLpm::create([
            'jabatan' => 'Anggota',
            'nama_lengkap' => 'Budi, M.Th.',
            'is_permanent' => false,
            'urutan' => 3,
        ]);

        $this->actingAs($user)->get(route('pengurus-lpm.create'))->assertStatus(403);
        $this->actingAs($user)->post(route('pengurus-lpm.store'), [])->assertStatus(403);
        $this->actingAs($user)->get(route('pengurus-lpm.edit', $pengurus->id))->assertStatus(403);
        $this->actingAs($user)->put(route('pengurus-lpm.update', $pengurus->id), [])->assertStatus(403);
        $this->actingAs($user)->delete(route('pengurus-lpm.destroy', $pengurus->id))->assertStatus(403);
    }

    /**
     * Test that users with permission can access management routes.
     */
    public function test_authorized_user_can_access_management_routes(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $pengurus = PengurusLpm::create([
            'jabatan' => 'Anggota',
            'nama_lengkap' => 'Budi, M.Th.',
            'is_permanent' => false,
            'urutan' => 3,
        ]);

        $this->actingAs($superadmin)->get(route('pengurus-lpm.create'))->assertStatus(200);
        $this->actingAs($superadmin)->get(route('pengurus-lpm.edit', $pengurus->id))->assertStatus(200);
    }

    /**
     * Test that permanent roles (Ketua/Sekretaris) cannot be deleted.
     */
    public function test_permanent_role_cannot_be_deleted(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $permanent = PengurusLpm::create([
            'jabatan' => 'Ketua',
            'nama_lengkap' => 'Sulistiono, M.Th.',
            'is_permanent' => true,
            'urutan' => 1,
        ]);

        $response = $this->actingAs($superadmin)
            ->delete(route('pengurus-lpm.destroy', $permanent->id));

        $response->assertRedirect(route('pengurus-lpm.index'));
        $response->assertSessionHas('error', 'Jabatan Ketua tidak dapat dihapus, hanya dapat diedit.');

        $this->assertDatabaseHas('pengurus_lpm', [
            'id' => $permanent->id,
            'jabatan' => 'Ketua',
        ]);
    }

    /**
     * Test that non-permanent roles (Anggota) can be deleted successfully.
     */
    public function test_non_permanent_role_can_be_deleted(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $anggota = PengurusLpm::create([
            'jabatan' => 'Anggota',
            'nama_lengkap' => 'Darmanto, M.Th.',
            'is_permanent' => false,
            'urutan' => 3,
        ]);

        $response = $this->actingAs($superadmin)
            ->delete(route('pengurus-lpm.destroy', $anggota->id));

        $response->assertRedirect(route('pengurus-lpm.index'));
        $response->assertSessionHas('success', 'Anggota berhasil dihapus.');

        $this->assertDatabaseMissing('pengurus_lpm', [
            'id' => $anggota->id,
        ]);
    }

    /**
     * Test that editing a permanent role preserves its jabatan.
     */
    public function test_editing_permanent_role_ignores_jabatan_change(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $permanent = PengurusLpm::create([
            'jabatan' => 'Ketua',
            'nama_lengkap' => 'Sulistiono, M.Th.',
            'is_permanent' => true,
            'urutan' => 1,
        ]);

        $response = $this->actingAs($superadmin)
            ->put(route('pengurus-lpm.update', $permanent->id), [
                'jabatan' => 'Anggota', // Attempt to downgrade jabatan
                'nama_lengkap' => 'Sulistiono, Ph.D.',
            ]);

        $response->assertRedirect(route('pengurus-lpm.index'));
        $response->assertSessionHas('success', 'Data pengurus berhasil diperbarui.');

        // Assert name was updated, but role/jabatan was NOT updated
        $this->assertDatabaseHas('pengurus_lpm', [
            'id' => $permanent->id,
            'jabatan' => 'Ketua',
            'nama_lengkap' => 'Sulistiono, Ph.D.',
        ]);
    }

    /**
     * Test hierarchy sorting (Ketua, Sekretaris, Anggota).
     */
    public function test_hierarchy_sorting_ascending(): void
    {
        $user = User::factory()->create();
        $user->assignRole('superadmin');

        // Create in reverse order
        PengurusLpm::create([
            'jabatan' => 'Anggota',
            'nama_lengkap' => 'Anggota A',
            'is_permanent' => false,
            'urutan' => 3,
        ]);
        PengurusLpm::create([
            'jabatan' => 'Sekretaris',
            'nama_lengkap' => 'Sekretaris A',
            'is_permanent' => true,
            'urutan' => 2,
        ]);
        PengurusLpm::create([
            'jabatan' => 'Ketua',
            'nama_lengkap' => 'Ketua A',
            'is_permanent' => true,
            'urutan' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('pengurus-lpm.index'));

        // Check if the response view data is ordered by urutan
        $pengurusList = $response->original->getData()['pengurusList'];
        $this->assertEquals('Ketua A', $pengurusList->get(0)->nama_lengkap);
        $this->assertEquals('Sekretaris A', $pengurusList->get(1)->nama_lengkap);
        $this->assertEquals('Anggota A', $pengurusList->get(2)->nama_lengkap);
    }

    /**
     * Test photo uploading on creation.
     */
    public function test_photo_upload_on_creation(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $file = \Illuminate\Http\UploadedFile::fake()->image('profile.jpg');

        $response = $this->actingAs($superadmin)
            ->post(route('pengurus-lpm.store'), [
                'jabatan' => 'Anggota',
                'nama_lengkap' => 'Budi, M.Th.',
                'foto' => $file,
            ]);

        $response->assertRedirect(route('pengurus-lpm.index'));

        // Check if the record is saved
        $pengurus = PengurusLpm::where('nama_lengkap', 'Budi, M.Th.')->first();
        $this->assertNotNull($pengurus->foto);

        // Check file exists in disk
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($pengurus->foto);
    }

    /**
     * Test photo replacement on update.
     */
    public function test_photo_replacement_on_update(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        // Create with initial fake photo
        $oldFile = \Illuminate\Http\UploadedFile::fake()->image('old.jpg');
        $oldPath = $oldFile->store('pengurus', 'public');

        $pengurus = PengurusLpm::create([
            'jabatan' => 'Anggota',
            'nama_lengkap' => 'Budi, M.Th.',
            'foto' => $oldPath,
            'is_permanent' => false,
            'urutan' => 3,
        ]);

        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($oldPath);

        // Upload new photo
        $newFile = \Illuminate\Http\UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($superadmin)
            ->put(route('pengurus-lpm.update', $pengurus->id), [
                'jabatan' => 'Anggota Baru',
                'nama_lengkap' => 'Budi Baru, M.Th.',
                'foto' => $newFile,
            ]);

        $response->assertRedirect(route('pengurus-lpm.index'));

        // Verify old photo is deleted
        \Illuminate\Support\Facades\Storage::disk('public')->assertMissing($oldPath);

        // Verify new photo is stored
        $pengurus->refresh();
        $this->assertNotEquals($oldPath, $pengurus->foto);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($pengurus->foto);
    }

    /**
     * Test photo deletion when record is destroyed.
     */
    public function test_photo_deleted_when_record_is_destroyed(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $file = \Illuminate\Http\UploadedFile::fake()->image('profile.jpg');
        $path = $file->store('pengurus', 'public');

        $pengurus = PengurusLpm::create([
            'jabatan' => 'Anggota',
            'nama_lengkap' => 'Budi, M.Th.',
            'foto' => $path,
            'is_permanent' => false,
            'urutan' => 3,
        ]);

        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($superadmin)
            ->delete(route('pengurus-lpm.destroy', $pengurus->id));

        $response->assertRedirect(route('pengurus-lpm.index'));

        // Verify file is physically deleted
        \Illuminate\Support\Facades\Storage::disk('public')->assertMissing($path);

        // Verify DB record is gone
        $this->assertDatabaseMissing('pengurus_lpm', [
            'id' => $pengurus->id,
        ]);
    }

    /**
     * Test photo removal when hapus_foto checkbox is checked.
     */
    public function test_photo_removal_via_checkbox(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $file = \Illuminate\Http\UploadedFile::fake()->image('profile.jpg');
        $path = $file->store('pengurus', 'public');

        $pengurus = PengurusLpm::create([
            'jabatan' => 'Anggota',
            'nama_lengkap' => 'Budi, M.Th.',
            'foto' => $path,
            'is_permanent' => false,
            'urutan' => 3,
        ]);

        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($superadmin)
            ->put(route('pengurus-lpm.update', $pengurus->id), [
                'jabatan' => 'Anggota',
                'nama_lengkap' => 'Budi, M.Th.',
                'hapus_foto' => '1',
            ]);

        $response->assertRedirect(route('pengurus-lpm.index'));

        // Verify file is physically deleted
        \Illuminate\Support\Facades\Storage::disk('public')->assertMissing($path);

        // Verify DB column is updated to null
        $pengurus->refresh();
        $this->assertNull($pengurus->foto);
    }
}
