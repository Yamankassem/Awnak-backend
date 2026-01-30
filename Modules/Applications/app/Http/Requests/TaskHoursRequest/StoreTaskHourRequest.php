<?php

namespace Modules\Applications\Http\Requests\TaskHoursRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskHourRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'task_id'       => 'required|exists:tasks,id',
            'hours'         => 'required|integer|min:1|max:12',
            'started_date'  => 'required|date',
            'ended_date'    => 'required|date',
            'note'          => 'required|string|max:500',
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
