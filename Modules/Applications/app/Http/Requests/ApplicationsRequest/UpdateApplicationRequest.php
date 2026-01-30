<?php

namespace Modules\Applications\Http\Requests\ApplicationsRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update Application Status Request
 * 
 * Validates application status update requests.
 * 
 * @package Modules\Applications\Http\Requests\ApplicationsRequest
 * @author Your Name
 */
class UpdateApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'opportunity_id'  => 'sometimes|exists:opportunities,id',
            'volunteer_id'    => 'sometimes|exists:volunteerProfiles,id',
            'coordinator_id'  => 'sometimes|exists:users,id',
            'assigned_at'     => 'nullable|date',
            'description'     => 'sometimes|string|min:10|max:2000',
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
