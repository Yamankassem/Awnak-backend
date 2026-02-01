<?php

namespace Modules\Applications\Http\Requests\TasksRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Index Task Request
 * 
 * Validates filtering and pagination parameters for listing tasks.
 * 
 * @package Modules\Applications\Http\Requests\TasksRequest
 * @author Your Name
 */
class IndexTaskRequest extends FormRequest
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
            'volunteer_profile_id'  => 'sometimes|exists:volunteerProfiles,id',
            'title'           => 'sometimes|string|max:255',
            'status'          => 'sometimes|in:preparation,active,complete,cancelled',
            'due_date_from'   => 'sometimes|date',
            'due_date_to'     => 'sometimes|date',
            'per_page'        => 'sometimes|integer|min:1|max:100',
            'page'            => 'sometimes|integer|min:1',
            'sort_by'         => 'sometimes|in:id,assigned_at,created_at,updated_at,status',
            'sort_order'      => 'sometimes|in:asc,desc',
            'search'          => 'sometimes|string|min:2|max:255',
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

    /**
     * Prepare validated data with default values.
     * 
     * @param string|null $key
     * @param mixed $default
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        
        $validated['per_page'] = $validated['per_page'] ?? 15;
        $validated['page'] = $validated['page'] ?? 1;
        $validated['sort_by'] = $validated['sort_by'] ?? 'due_date';
        $validated['sort_order'] = $validated['sort_order'] ?? 'asc';
        
        return $validated;
    }
}
