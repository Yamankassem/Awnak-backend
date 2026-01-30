<?php

namespace Modules\Applications\Http\Requests\FeedbacksRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'task_id'       => 'required|exists:tasks,id',
            'name_of_org'   => 'required|string|max:255',
            'name_of_vol'   => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:12',
            'comment'       => 'required|string|min:10|max:1000',
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
