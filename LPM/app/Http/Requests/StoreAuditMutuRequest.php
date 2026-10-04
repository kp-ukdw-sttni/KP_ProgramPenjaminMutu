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
            'temuan'                    => ['required', 'array', 'min:1'],
            'temuan.*.kategori_temuan'  => ['required', 'string', Rule::in(array_column(KategoriTemuan::cases(), 'value'))],
            'temuan.*.deskripsi_temuan' => ['required', 'string'],
            'temuan.*.rekomendasi'      => ['nullable', 'string'],
            'nilai'                     => ['nullable', 'integer', 'min:1', 'max:4'],
        ];
    }

    public function attributes(): array
    {
        return [
            'temuan.*.kategori_temuan'  => 'Kategori Temuan',
            'temuan.*.deskripsi_temuan' => 'Deskripsi Temuan',
            'temuan.*.rekomendasi'      => 'Rekomendasi',
            'nilai'                     => 'Nilai',
        ];
    }
}
