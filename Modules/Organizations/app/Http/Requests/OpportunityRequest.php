<?php

namespace Modules\Organizations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request: OpportunityRequest
 *
 * Handles validation rules for creating or updating opportunities.
 */
class OpportunityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * For now, return true to allow all requests.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * These rules ensure that opportunity data is valid before saving.
     */
    public function rules(): array
    {

        return [
            'title'          => 'required|string|max:255', // Opportunity title must be provided
            'description'    => 'nullable|string',         // Optional description
            'type'           => 'nullable|string|max:100', // Optional type (volunteering, training, job, etc.)
            'start_date'     => 'nullable|date',           // Optional start date
            'end_date'       => 'nullable|date|after_or_equal:start_date', // End date must be after start date
            'skills'         => 'nullable|array',          // Optional
            'status'         =>  'in:approved,rejected,pending', // Opportunity's Status default pending
            'organization_id' => 'required|exists:organizations,id', // Must reference a valid organization
            'address'       =>'nullable|string',
            'longitude'     => 'nullable|decimal:8,11',
            'latitude'      => 'nullable|decimal:8,10'
        ];
    }
}
