<?php

namespace Modules\Applications\Http\Requests\ApplicationRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'opportunity_id'    => 'required|exists:opportunities,id',
            'volunteer_id'      => 'required|exists:volunteers,id',
            'coordinator_id'    => 'required|exists:coordinators,id',
            'assigned_at'       => 'nullable|date',
            'description'       => 'required|string|min:10|max:2000',
            'status'            => 'required|in:pending,approved,rejected',
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
