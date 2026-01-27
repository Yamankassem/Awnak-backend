<?php

namespace Modules\Applications\Http\Requests\CalendarRequest;

use Illuminate\Foundation\Http\FormRequest;

class IndexCalendarRequest extends FormRequest
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
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'type' => 'sometimes|string|in:task,meeting,training,deadline,event,reminder,all',
            'related_type' => 'sometimes|string|max:255',
            'related_id' => 'sometimes|integer|min:1',
            'status' => 'sometimes|string|in:pending,confirmed,cancelled',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'start_date.required' => 'تاريخ البداية مطلوب',
            'start_date.date' => 'تاريخ البداية يجب أن يكون تاريخ صالح',
            'start_date.date_format' => 'تنسيق تاريخ البداية يجب أن يكون Y-m-d',
            'end_date.required' => 'تاريخ النهاية مطلوب',
            'end_date.date' => 'تاريخ النهاية يجب أن يكون تاريخ صالح',
            'end_date.date_format' => 'تنسيق تاريخ النهاية يجب أن يكون Y-m-d',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
            'type.in' => 'النوع يجب أن يكون: task, meeting, training, deadline, event, reminder, all',
            'status.in' => 'الحالة يجب أن تكون: pending, confirmed, cancelled',
        ];
    }
}