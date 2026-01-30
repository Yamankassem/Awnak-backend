<?php

namespace Modules\Applications\Http\Requests\CalendarRequest;

use Illuminate\Foundation\Http\FormRequest;

class RemindersCalendarRequest extends FormRequest
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
            'hours' => 'sometimes|integer|min:1|max:24',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'hours.min' => 'عدد الساعات يجب أن يكون 1 على الأقل',
            'hours.max' => 'عدد الساعات يجب ألا يتجاوز 24',
        ];
    }
}