<?php

namespace App\Imports;

use App\Models\StandarMutu;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StandarMutuImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Basic validation: ignore if key columns are missing or empty
            if (empty($row['kode_standar']) && empty($row['nama_standar'])) {
                continue;
            }

            $id = isset($row['id']) && is_numeric($row['id']) ? (int) $row['id'] : null;

            $data = [
                'kode_standar'      => $row['kode_standar'] ?? null,
                'nama_standar'      => $row['nama_standar'] ?? null,
                'deskripsi'         => $row['deskripsi'] ?? null,
                'indikator_kinerja' => $row['indikator_kinerja'] ?? null,
                'target_capaian'    => $row['target_capaian'] ?? null,
            ];

            if ($id && $existing = StandarMutu::find($id)) {
                $existing->update($data);
            } else {
                // Fallback to update by kode_standar if existing in database to avoid unique key conflicts
                if (!empty($data['kode_standar']) && $existingKode = StandarMutu::where('kode_standar', $data['kode_standar'])->first()) {
                    $existingKode->update($data);
                } else {
                    StandarMutu::create($data);
                }
            }
        }
    }
}
