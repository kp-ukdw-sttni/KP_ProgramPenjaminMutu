<?php

namespace Tests\Feature;

use App\Models\Fakultas;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FakultasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_superadmin_can_crud_fakultas(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        // 1. Create
        $response = $this->actingAs($superadmin)
            ->post(route('fakultas.store'), [
                'nama_fakultas' => 'Fakultas Teknologi Informasi',
                'singkatan' => 'FTI',
            ]);

        $response->assertRedirect(route('fakultas.index'));
        $this->assertDatabaseHas('fakultas', [
            'nama_fakultas' => 'Fakultas Teknologi Informasi',
            'singkatan' => 'FTI',
        ]);

        $fakultas = Fakultas::first();

        // 2. Edit View
        $response = $this->actingAs($superadmin)
            ->get(route('fakultas.edit', $fakultas));

        $response->assertStatus(200);
        $response->assertSee('Fakultas Teknologi Informasi');

        // 3. Update
        $response = $this->actingAs($superadmin)
            ->put(route('fakultas.update', $fakultas), [
                'nama_fakultas' => 'Fakultas Teknologi Informasi Baru',
                'singkatan' => 'FTIB',
            ]);

        $response->assertRedirect(route('fakultas.index'));
        $this->assertDatabaseHas('fakultas', [
            'id' => $fakultas->id,
            'nama_fakultas' => 'Fakultas Teknologi Informasi Baru',
            'singkatan' => 'FTIB',
        ]);

        // 4. Delete
        $response = $this->actingAs($superadmin)
            ->delete(route('fakultas.destroy', $fakultas));

        $response->assertRedirect(route('fakultas.index'));
        $this->assertDatabaseMissing('fakultas', [
            'id' => $fakultas->id,
        ]);
    }
}
