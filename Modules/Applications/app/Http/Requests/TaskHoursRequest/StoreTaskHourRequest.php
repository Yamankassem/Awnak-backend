<?php

namespace Modules\Applications\Http\Requests\TaskHoursRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store TaskHour Request
 * 
 * Validates taskHour creation requests.
 * 
 * @package Modules\Applications\Http\Requests\TaskHoursRequest
 * @author Your Name
 */
class StoreTaskHourRequest extends FormRequest
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
            'hours'         => 'required|integer|min:1|max:12',
            'started_date'  => 'required|date',
            'ended_date'    => 'required|date',
            'note'          => 'required|string|max:500',
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
