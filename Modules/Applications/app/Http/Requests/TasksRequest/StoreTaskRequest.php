<?php

namespace Modules\Applications\Http\Requests\TasksRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store Task Request
 * 
 * Validates task creation requests.
 * 
 * @package Modules\Applications\Http\Requests\TasksRequest
 * @author Your Name
 */
class StoreTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'application_id'  => 'required|exists:applications,id',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string|min:10',
            'status'          => 'required|in:active,complete',
            'due_date'        => 'required|date',
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
