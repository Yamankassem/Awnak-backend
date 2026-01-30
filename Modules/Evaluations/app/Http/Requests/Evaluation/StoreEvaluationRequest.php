<?php

namespace Modules\Evaluations\Http\Requests\Evaluation;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'task_id' => 'required|integer',
            'volunteer_id' => 'required|integer',
            'score' => 'required|numeric|min:0|max:100',
            'improvement' => 'nullable|string|max:1000',
            'strengths' => 'nullable|string|max:1000',
        ];
    }

   
    
}
