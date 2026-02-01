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
class UpdateStatusTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status'      => 'sometimes|in:preparation,active,complete,cancelled',
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
