<?php

namespace Modules\Evaluations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'score' => 'nullable|numeric|min:0|max:100',
            'improvement' => 'nullable|string|max:1000',
            'strengths' => 'nullable|string|max:1000',
        ];
    }
}
