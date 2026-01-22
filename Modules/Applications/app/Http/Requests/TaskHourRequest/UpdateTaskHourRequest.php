<?php

namespace Modules\Applications\Http\Requests\TaskHourRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskHourRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'task_id'       => 'sometimes|exists:tasks,id',
            'hours'         => 'sometimes|integer|min:1|max:12',
            'started_date'  => 'sometimes|date',
            'ended_date'    => 'sometimes|date',
            'note'          => 'sometimes|string|max:500',
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
