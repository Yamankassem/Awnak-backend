<?php

namespace Modules\Applications\Http\Requests\ApplicationsRequest;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store Application Request
 * 
 * Validates application creation requests.
 * 
 * @package Modules\Applications\Http\Requests\ApplicationsRequest
 * @author Your Name
 */
class StoreApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'opportunity_id'    => 'required|exists:opportunities,id',
            'volunteer_profile_id'      => 'required|exists:volunteerProfiles,id',
            'coordinator_id'    => 'required|exists:users,id',
            'assigned_at'       => 'nullable|date',
            'description'       => 'required|string|min:10|max:2000',
            'status'            => 'required|in:pending,approved,rejected',
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
