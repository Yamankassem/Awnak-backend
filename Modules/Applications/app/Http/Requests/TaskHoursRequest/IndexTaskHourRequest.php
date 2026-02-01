<?php

namespace Modules\Applications\Http\Requests\TaskHoursRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Index TaskHour Request
 * 
 * Validates filtering and pagination parameters for listing task hours.
 * 
 * @package Modules\Applications\Http\Requests\TaskHoursRequest
 * @author Your Name
 */
class IndexTaskHourRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_id'         => 'sometimes|exists:tasks,id',
            'application_id'  => 'sometimes|exists:applications,id',
            'volunteer_profile_id'    => 'sometimes|exists:volunteerProfiles,id',
            'hours'           => 'sometimes|integer|min:1|max:12',
            'started_date_from'    => 'sometimes|date',
            'started_date_to'    => 'sometimes|date',
            'ended_date_from'      => 'sometimes|date',
            'ended_date_to'      => 'sometimes|date',
            'per_page'        => 'sometimes|integer|min:1|max:100',
            'page'            => 'sometimes|integer|min:1',
            'sort_by'         => 'sometimes|in:id,assigned_at,created_at,updated_at,status',
            'sort_order'      => 'sometimes|in:asc,desc',
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
        $validated['sort_by'] = $validated['sort_by'] ?? 'started_date';
        $validated['sort_order'] = $validated['sort_order'] ?? 'desc';
        
        return $validated;
    }
}
