<?php

namespace Modules\Applications\Http\Requests\CalendarRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpcomingCalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'days' => 'sometimes|integer|min:1|max:30',
            'type' => 'sometimes|string|in:task,meeting,training,deadline,event,reminder,all',
            'limit' => 'sometimes|integer|min:1|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'days.min' => 'عدد الأيام يجب أن يكون 1 على الأقل',
            'days.max' => 'عدد الأيام يجب ألا يتجاوز 30',
            'type.in' => 'النوع يجب أن يكون: task, meeting, training, deadline, event, reminder, all',
            'limit.min' => 'الحد الأدنى للنتائج يجب أن يكون 1 على الأقل',
            'limit.max' => 'الحد الأقصى للنتائج يجب ألا يتجاوز 100',
        ];
    }
}