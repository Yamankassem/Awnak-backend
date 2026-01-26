<?php

namespace Modules\Evaluations\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id' => 'required|exists:tasks,id',
            'hours'   => 'required|integer|min:1',
            'context' => 'nullable|string',
            'issued_at' => 'nullable|date',
        ];
    }
}
