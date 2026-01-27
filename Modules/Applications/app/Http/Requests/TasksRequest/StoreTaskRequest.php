<?php

namespace Modules\Applications\Http\Requests\TasksRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
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
     */
    public function authorize(): bool
    {
        return true;
    }
}
