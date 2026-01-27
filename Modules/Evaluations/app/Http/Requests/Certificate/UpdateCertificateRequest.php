<?php

namespace Modules\Evaluations\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hours'   => 'sometimes|integer|min:1',
            'context' => 'nullable|string',
            'issued_at' => 'nullable|date',
        ];
    }
}
