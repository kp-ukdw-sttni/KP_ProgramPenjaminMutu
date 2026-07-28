<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStandarMutuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-standar');
    }

    public function rules(): array
    {
        return [
            'kode_standar'     => ['required', 'string', 'max:30', Rule::unique('standar_mutu', 'kode_standar')->ignore($this->route('standar_mutu'))],
            'nama_standar'     => ['required', 'string', 'max:255'],
            'deskripsi'        => ['nullable', 'string'],
            'indikator_kinerja'=> ['nullable', 'string'],
            'target_capaian'   => ['nullable', 'string', 'max:100'],
        ];
    }
}
