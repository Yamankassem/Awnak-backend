<?php

namespace Modules\Applications\Http\Requests\ApplicationsRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'opportunity_id'  => 'sometimes|exists:opportunities,id',
            'volunteer_id'    => 'sometimes|exists:volunteerProfiles,id',
            'coordinator_id'  => 'sometimes|exists:users,id',
            'assigned_at'     => 'nullable|date',
            'description'     => 'sometimes|string|min:10|max:2000',
            'status'          => 'sometimes|in:pending,approved,rejected',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
