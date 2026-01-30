<?php

namespace Modules\Applications\Http\Requests\FeedbacksRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update Feedback Status Request
 * 
 * Validates feedback status update requests.
 * 
 * @package Modules\Applications\Http\Requests\FeedbacksRequest
 * @author Your Name
 */
class UpdateFeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_id'       => 'sometimes|exists:tasks,id',
            'name_of_org'   => 'sometimes|string|max:255',
            'name_of_vol'   => 'sometimes|string|max:255',
            'rating'        => 'sometimes|integer|min:1|max:12',
            'comment'       => 'sometimes|string|min:10|max:1000',
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
