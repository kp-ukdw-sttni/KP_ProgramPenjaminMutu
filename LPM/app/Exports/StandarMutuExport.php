<?php

namespace App\Exports;

use App\Models\StandarMutu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StandarMutuExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return StandarMutu::all();
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID',
            'Kode Standar',
            'Nama Standar',
            'Deskripsi',
            'Indikator Kinerja',
            'Target Capaian',
        ];
    }

    /**
    * @param mixed $row
    *
    * @return array
    */
    public function map($row): array
    {
        return [
            $row->id,
            $row->kode_standar,
            $row->nama_standar,
            $row->deskripsi,
            $row->indikator_kinerja,
            $row->target_capaian,
        ];
    }
}
