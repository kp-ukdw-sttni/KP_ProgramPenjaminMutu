<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-users');
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'unique:users,email'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
            'roles'            => ['required', 'array', 'min:1'],
            'roles.*'          => ['string', 'exists:roles,name'],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ];
    }
}
