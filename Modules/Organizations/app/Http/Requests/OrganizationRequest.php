<?php

namespace Modules\Organizations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * Request: OrganizationRequest
 *
 * Validates incoming data for creating or updating organizations.
 */
class OrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Allow all for now, adjust if you add authorization logic
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'license_number' => 'required|string|max:255|unique:organizations,license_number',
            'type'           => 'required|string|max:100',
            'bio'            => 'nullable|string',
            'website'        => 'nullable|url|max:255',
        ];
    }
}
