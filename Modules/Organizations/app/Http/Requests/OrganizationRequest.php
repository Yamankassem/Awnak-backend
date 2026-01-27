<?php

namespace Modules\Organizations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request: OrganizationRequest
 *
 * Validates incoming data for creating or updating organizations.
 * Ensures that required fields are present and meet constraints.
 *
 * Fields:
 * - license_number: Required, unique string identifier for the organization.
 * - type: Required string indicating the type of organization (e.g., NGO, school, charity).
 * - bio: Optional string providing background or description.
 * - website: Optional, must be a valid URL if provided.
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
            'user_id'        => 'required|exists:users,id',
        ];
    }
}

