<?php

namespace Modules\Applications\Http\Requests\TasksRequest;

use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'application_id'  => 'sometimes|exists:applications,id',
            'volunteer_id'  => 'sometimes|exists:volunteerProfiles,id',
            'title'           => 'sometimes|string|max:255',
            'status'          => 'sometimes|in:active,complete',
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
     */
    public function authorize(): bool
    {
        return true;
    }

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
