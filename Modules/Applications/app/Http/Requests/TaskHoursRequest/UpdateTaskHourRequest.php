<?php

namespace Modules\Applications\Http\Requests\TaskHoursRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update TasHour Status Request
 * 
 * Validates tasHour status update requests.
 * 
 * @package Modules\Applications\Http\Requests\TasHoursRequest
 * @author Your Name
 */
class UpdateTaskHourRequest extends FormRequest
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
            'hours'         => 'sometimes|integer|min:1|max:12',
            'started_date'  => 'sometimes|date',
            'ended_date'    => 'sometimes|date',
            'note'          => 'sometimes|string|max:500',
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
