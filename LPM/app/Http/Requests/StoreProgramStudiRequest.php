<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramStudiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-users');
    }

    public function rules(): array
    {
        return [
            'fakultas_id'  => ['nullable', 'exists:fakultas,id'],
            'nama_prodi'   => ['required', 'string', 'max:255'],
            'kepala_prodi' => ['nullable', 'string', 'max:255'],
        ];
    }
}
