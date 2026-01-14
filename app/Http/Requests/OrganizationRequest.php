<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
{
    /** * Determine if the user is authorized to make this request. *
     *  * For now, return true to allow all requests. *
     *  You can add authorization logic later if needed. */

    public function authorize(): bool
    {
        return true;
    }
    /** * Get the validation rules that apply to the request. *
     * * Validation rules for creating or updating an organization:
     * * - license_number: required, string, unique *
     *  - type: required, string * - bio: optional, string *
     * - website: optional, valid URL */

    public function rules(): array
    {
        return [
            'license_number' => 'required|string|unique:organizations,license_number,'
                . $this->id,
            'type' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'website' => 'nullable|url',
        ];
    }

    /** * Custom error messages for validation. *
     * * These messages will be returned when validation fails. */

    public function messages(): array
    {
        return [
            'license_number.required' => 'License number is required.',
            'license_number.unique' => 'This license number is already taken.',
            'type.required' => 'Organization type is required.',
            'website.url' => 'Website must be a valid URL.',
        ];
    }
}
