<?php

namespace Modules\Applications\Http\Requests\NotificationRequest;

use Illuminate\Foundation\Http\FormRequest;

class IndexNotificationRequest extends FormRequest
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
            'per_page' => 'sometimes|integer|min:1|max:100',
            'type' => 'sometimes|string|in:new_application,application_status_changed,new_task,task_status_changed,hours_logged,new_feedback,system,reminder,report',
            'unread_only' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'per_page.min' => 'عدد النتائج لكل صفحة يجب أن يكون 1 على الأقل',
            'per_page.max' => 'عدد النتائج لكل صفحة يجب ألا يتجاوز 100',
            'type.in' => 'النوع غير صالح',
            'unread_only.boolean' => 'حقل القراءة فقط يجب أن يكون قيمة منطقية',
        ];
    }
}