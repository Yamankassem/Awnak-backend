<?php

namespace Modules\Applications\Http\Requests\FeedbackRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
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
     */
    public function authorize(): bool
    {
        return true;
    }
}
