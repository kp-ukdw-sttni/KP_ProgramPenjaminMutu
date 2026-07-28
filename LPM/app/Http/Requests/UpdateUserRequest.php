<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-users');
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password'         => ['nullable', Password::defaults(), 'confirmed'],
            'roles'            => ['required', 'array', 'min:1'],
            'roles.*'          => ['string', 'exists:roles,name'],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ];
    }
}
