<?php

namespace App\Http\Requests;

use App\Enums\Semester;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreEvaluasiDiriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-evaluasi');
    }

    public function rules(): array
    {
        return [
            'standar_mutu_id'        => ['required', 'exists:standar_mutu,id'],
            'program_studi_id'       => ['required', 'exists:program_studi,id'],
            'tahun_akademik'         => ['required', 'string', 'max:20'],
            'semester'               => ['required', 'string', Rule::in(array_column(Semester::cases(), 'value'))],
            'capaian_aktual'         => ['nullable', 'string', 'max:255'],
            'deskripsi_ketercapaian' => ['nullable', 'string'],
            'file_bukti_fisik'       => [
                'nullable',
                File::types(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'zip'])
                    ->max(10 * 1024), // 10 MB
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'standar_mutu_id'        => 'Standar Mutu',
            'program_studi_id'       => 'Program Studi',
            'tahun_akademik'         => 'Tahun Akademik',
            'semester'               => 'Semester',
            'capaian_aktual'         => 'Capaian Aktual',
            'deskripsi_ketercapaian' => 'Deskripsi Ketercapaian',
            'file_bukti_fisik'       => 'Bukti Fisik',
        ];
    }
}
