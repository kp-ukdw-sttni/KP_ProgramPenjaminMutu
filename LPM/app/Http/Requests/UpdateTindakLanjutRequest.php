<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTindakLanjutRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only auditee can respond with corrective action
        return $this->user()->can('respond-audit');
    }

    public function rules(): array
    {
        return [
            'rencana_tindak_lanjut' => ['required', 'string', 'min:20'],
        ];
    }

    public function attributes(): array
    {
        return [
            'rencana_tindak_lanjut' => 'Rencana Tindak Lanjut (CAPA)',
        ];
    }
}
