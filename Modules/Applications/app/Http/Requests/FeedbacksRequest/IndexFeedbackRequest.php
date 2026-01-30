<?php

namespace Modules\Applications\Http\Requests\FeedbacksRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Index Feedback Request
 * 
 * Validates filtering and pagination parameters for listing feedbacks.
 * 
 * @package Modules\Applications\Http\Requests\FeedbacksRequest
 * @author Your Name
 */
class IndexFeedbackRequest extends FormRequest
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
            'name_of_org'     => 'sometimes|string|max:255',
            'name_of_vol'     => 'sometimes|string|max:255',
            'rating'          => 'sometimes|integer|min:1|max:12',
            'comment'         => 'sometimes|string|min:10|max:1000',
            'from_date'       => 'sometimes|date',
            'to_date'         => 'sometimes|date',
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
        $validated['sort_by'] = $validated['sort_by'] ?? 'created_at';
        $validated['sort_order'] = $validated['sort_order'] ?? 'desc';
        
        return $validated;
    }
}