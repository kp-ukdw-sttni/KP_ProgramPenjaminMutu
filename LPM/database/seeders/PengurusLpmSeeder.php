<?php

namespace Database\Seeders;

use App\Models\PengurusLpm;
use Illuminate\Database\Seeder;

class PengurusLpmSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'jabatan' => 'Ketua',
                'nama_lengkap' => 'Sulistiono, M.Th.',
                'is_permanent' => true,
                'urutan' => 1,
                'email' => 'lpm@sttni.ac.id',
            ],
            [
                'jabatan' => 'Sekretaris',
                'nama_lengkap' => 'Nevy Tri Kasih Daeli, M.Pd.',
                'is_permanent' => false,
                'urutan' => 2,
                'email' => null,
            ],
            [
                'jabatan' => 'Anggota',
                'nama_lengkap' => 'Dr. Seri Damarwanti, S.E., M.Th.',
                'is_permanent' => false,
                'urutan' => 3,
            ],
            [
                'jabatan' => 'Anggota',
                'nama_lengkap' => 'Sapto Sunariyanti, M.Th.',
                'is_permanent' => false,
                'urutan' => 4,
            ],
            [
                'jabatan' => 'Anggota',
                'nama_lengkap' => 'Darmanto, M.Th.',
                'is_permanent' => false,
                'urutan' => 5,
            ],
            [
                'jabatan' => 'Anggota',
                'nama_lengkap' => 'Billy W.S. Kapoyos, M.Th.',
                'is_permanent' => false,
                'urutan' => 6,
            ],
            [
                'jabatan' => 'Anggota',
                'nama_lengkap' => 'Suwarni M.Th.',
                'is_permanent' => false,
                'urutan' => 7,
            ],
            [
                'jabatan' => 'Anggota',
                'nama_lengkap' => 'Sukirdi, M.Th.',
                'is_permanent' => false,
                'urutan' => 8,
            ],
        ];

        foreach ($data as $item) {
            PengurusLpm::updateOrCreate(
                ['jabatan' => $item['jabatan'], 'nama_lengkap' => $item['nama_lengkap']],
                $item
            );
        }
    }
}
