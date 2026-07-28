<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFakultasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-users');
    }

    public function rules(): array
    {
        return [
            'nama_fakultas' => ['required', 'string', 'max:255'],
            'singkatan'     => ['nullable', 'string', 'max:20'],
        ];
    }
}
