<?php

namespace Modules\Applications\Http\Requests\TasksRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update Task Status Request
 * 
 * Validates task status update requests.
 * 
 * @package Modules\Applications\Http\Requests\TasksRequest
 * @author Your Name
 */
class UpdateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'application_id'  => 'sometimes|exists:applications,id',
            'title'           => 'sometimes|string|max:255',
            'description'     => 'sometimes|string|min:10',
            'due_date'        => 'sometimes|date',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
