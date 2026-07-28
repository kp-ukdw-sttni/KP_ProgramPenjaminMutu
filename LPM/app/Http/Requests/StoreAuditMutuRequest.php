<?php

namespace App\Http\Requests;

use App\Enums\KategoriTemuan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAuditMutuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-audit');
    }

    public function rules(): array
    {
        return [
            'evaluasi_diri_id' => ['required', 'exists:evaluasi_diri,id'],
            'kategori_temuan'  => ['required', 'string', Rule::in(array_column(KategoriTemuan::cases(), 'value'))],
            'deskripsi_temuan' => ['required', 'string'],
            'rekomendasi'      => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'evaluasi_diri_id' => 'Evaluasi Diri',
            'kategori_temuan'  => 'Kategori Temuan',
            'deskripsi_temuan' => 'Deskripsi Temuan',
            'rekomendasi'      => 'Rekomendasi',
        ];
    }
}
