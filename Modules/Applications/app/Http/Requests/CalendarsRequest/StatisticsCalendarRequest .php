<?php

namespace Modules\Applications\Http\Requests\CalendarRequest;

use Illuminate\Foundation\Http\FormRequest;

class StatisticsCalendarRequest extends FormRequest
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
        $currentYear = date('Y');
        
        return [
            'month' => 'sometimes|integer|min:1|max:12',
            'year' => 'sometimes|integer|min:2020|max:' . ($currentYear + 10),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'month.min' => 'الشهر يجب أن يكون بين 1 و 12',
            'month.max' => 'الشهر يجب أن يكون بين 1 و 12',
            'year.min' => 'السنة يجب أن تكون 2020 على الأقل',
            'year.max' => 'السنة يجب ألا تتجاوز ' . (date('Y') + 10),
        ];
    }
}