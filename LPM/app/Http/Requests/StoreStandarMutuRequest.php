<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStandarMutuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-standar');
    }

    public function rules(): array
    {
        return [
            'kode_standar'     => ['required', 'string', 'max:30', 'unique:standar_mutu,kode_standar'],
            'nama_standar'     => ['required', 'string', 'max:255'],
            'deskripsi'        => ['nullable', 'string'],
            'indikator_kinerja'=> ['nullable', 'string'],
            'target_capaian'   => ['nullable', 'string', 'max:100'],
        ];
    }
}
