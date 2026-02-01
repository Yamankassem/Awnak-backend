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
        $rules = [
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'type'           => 'nullable|string|max:100',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'skills'         => 'nullable|array',
            'status'         => 'in:approved,rejected,pending',
            'organization_id' => 'required|exists:organizations,id',
            'address'        => 'nullable|string',
            'location_id'    => 'nullable|exists:locations,id',
            'location'       => 'nullable|array',
        ];

        // إذا ما في location_id، لازم نطلب تفاصيل الموقع
        if (!$this->has('location_id')) {
            $rules['location.name'] = $this->isMethod('post') ? 'required|string|max:255' : 'nullable|string|max:255';
            $rules['location.type'] = $this->isMethod('post') ? 'required|string|max:50' : 'nullable|string|max:50';
            $rules['location.lat']  = $this->isMethod('post') ? 'required|numeric' : 'nullable|numeric';
            $rules['location.lng']  = $this->isMethod('post') ? 'required|numeric' : 'nullable|numeric';
        }

        return $rules;
    }
}
