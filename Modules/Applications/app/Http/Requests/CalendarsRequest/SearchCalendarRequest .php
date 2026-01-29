<?php

namespace Modules\Applications\Http\Requests\CalendarRequest;

use Illuminate\Foundation\Http\FormRequest;

class SearchCalendarRequest extends FormRequest
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
            'query' => 'required|string|min:2|max:100',
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d',
            'type' => 'nullable|string|in:task,meeting,training,deadline,event,reminder',
            'per_page' => 'sometimes|integer|min:1|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'query.required' => 'كلمة البحث مطلوبة',
            'query.min' => 'كلمة البحث يجب أن تتكون من حرفين على الأقل',
            'query.max' => 'كلمة البحث يجب ألا تتجاوز 100 حرف',
            'start_date.date' => 'تاريخ البداية يجب أن يكون تاريخ صالح',
            'end_date.date' => 'تاريخ النهاية يجب أن يكون تاريخ صالح',
            'type.in' => 'النوع يجب أن يكون: task, meeting, training, deadline, event, reminder',
            'per_page.min' => 'عدد النتائج لكل صفحة يجب أن يكون 1 على الأقل',
            'per_page.max' => 'عدد النتائج لكل صفحة يجب ألا يتجاوز 50',
        ];
    }
}