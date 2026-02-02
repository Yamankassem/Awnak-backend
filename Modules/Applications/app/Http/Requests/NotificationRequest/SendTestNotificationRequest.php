<?php

namespace Modules\Applications\Http\Requests\NotificationRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Send Test Notification Request
 * 
 * Validates parameters for sending test notifications.
 * 
 * @package Modules\Applications\Http\Requests\NotificationRequest
 * @author Your Name
 */
class SendTestNotificationRequest extends FormRequest
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
            'type' => 'sometimes|string|in:new_application,application_status_changed,new_task,task_status_changed,hours_logged,new_feedback,system,reminder,report',
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
            'type.in' => 'The notification type is invalid',
        ];
    }
}