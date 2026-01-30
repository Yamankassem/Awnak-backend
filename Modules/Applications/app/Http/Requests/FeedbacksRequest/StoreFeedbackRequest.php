<?php

namespace Modules\Applications\Http\Requests\FeedbacksRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store Feedback Request
 * 
 * Validates feedback creation requests.
 * 
 * @package Modules\Applications\Http\Requests\FeedbacksRequest
 * @author Your Name
 */
class StoreFeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_id'       => 'required|exists:tasks,id',
            'name_of_org'   => 'required|string|max:255',
            'name_of_vol'   => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:12',
            'comment'       => 'required|string|min:10|max:1000',
            'metrics' => 'sometimes|array|max:5',
            'metrics.*.name' => 'required_with:metrics|string|in:commitment,quality,collaboration,punctuality,initiative|distinct',
            'metrics.*.score' => 'required_with:metrics|integer|min:1|max:5',
            'metrics.*.notes' => 'nullable|string|max:500',
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
