<?php

namespace Modules\Applications\Http\Requests\CalendarRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreCalendarRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|date_format:Y-m-d H:i:s',
            'end_date' => 'nullable|date|date_format:Y-m-d H:i:s|after_or_equal:start_date',
            'type' => 'required|string|in:task,meeting,training,deadline,event,reminder',
            'related_type' => 'nullable|string|max:255',
            'related_id' => 'nullable|integer|min:1',
            'color' => 'nullable|string|max:7|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'is_all_day' => 'boolean',
            'location' => 'nullable|string|max:500',
            'reminder_minutes' => 'nullable|integer|min:0|max:10080',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('start_date') && !str_contains($this->start_date, ':')) {
            $this->merge([
                'start_date' => $this->start_date . ' 00:00:00',
            ]);
        }

        if ($this->has('end_date') && $this->end_date && !str_contains($this->end_date, ':')) {
            $this->merge([
                'end_date' => $this->end_date . ' 23:59:59',
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'العنوان مطلوب',
            'title.max' => 'العنوان يجب ألا يتجاوز 255 حرف',
            'start_date.required' => 'تاريخ البداية مطلوب',
            'start_date.date' => 'تاريخ البداية يجب أن يكون تاريخ صالح',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
            'type.required' => 'النوع مطلوب',
            'type.in' => 'النوع يجب أن يكون: task, meeting, training, deadline, event, reminder',
            'color.regex' => 'صيغة اللون غير صحيحة (مثال: #FFFFFF أو #FFF)',
            'reminder_minutes.min' => 'دقائق التذكير يجب أن تكون 0 أو أكثر',
            'reminder_minutes.max' => 'دقائق التذكير يجب ألا تتجاوز 10080 دقيقة (أسبوع)',
        ];
    }
}