<?php

namespace Modules\Applications\Http\Requests\NotificationRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Index Notification Request
 * 
 * Validates filtering parameters for listing notifications.
 * 
 * @package Modules\Applications\Http\Requests\NotificationRequest
 * @author Your Name
 */
class IndexNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool
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
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'per_page.min' => 'The number of results per page must be at least 1',
            'per_page.max' => 'The number of results per page must not exceed 100',
            'type.in' => 'Invalid type',
            'unread_only.boolean' => 'The read-only field must be a boolean value',
        ];
    }
}