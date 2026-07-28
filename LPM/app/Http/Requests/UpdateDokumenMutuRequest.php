<?php

namespace App\Http\Requests;

use App\Enums\KategoriDokumen;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateDokumenMutuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-dokumen');
    }

    public function rules(): array
    {
        return [
            'kategori'       => ['required', 'string', Rule::in(array_column(KategoriDokumen::cases(), 'value'))],
            'judul'          => ['required', 'string', 'max:255'],
            'nomor_dokumen'  => ['nullable', 'string', 'max:100'],
            'tahun_berlaku'  => ['nullable', 'integer', 'digits:4', 'min:2000', 'max:2099'],
            'semester'       => ['required', Rule::enum(\App\Enums\Semester::class)],
            'is_active'      => ['boolean'],
            'file'           => [
                'nullable',
                File::types(['pdf', 'doc', 'docx'])
                    ->max(5 * 1024), // 5 MB
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori'      => 'Kategori Dokumen',
            'judul'         => 'Judul',
            'nomor_dokumen' => 'Nomor Dokumen',
            'tahun_berlaku' => 'Tahun Berlaku',
            'semester'      => 'Semester',
            'is_active'     => 'Status Aktif',
            'file'          => 'File Dokumen',
        ];
    }
}
