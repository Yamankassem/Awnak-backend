<?php

namespace Modules\Applications\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'application_id'  => 'sometimes|exists:applications,id',
            'title'           => 'sometimes|string|max:255',
            'description'     => 'sometimes|string|min:10',
            'status'          => 'sometimes|in:active,complete',
            'due_date'        => 'sometimes|date',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
