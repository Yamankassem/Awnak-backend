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
 * - user_id: Required only when creating a new organization.
 * - status: Required for system-admin on create, optional (nullable) on update.
 *
 * Rules:
 * - POST (create):
 *   - license_number: required, unique
 *   - type: required
 *   - bio: optional
 *   - website: optional, must be valid URL
 *   - user_id: required, must exist in users table
 *   - status: required if user has role system-admin (values: active, notactive)
 *
 * - PUT/PATCH (update):
 *   - license_number: required, unique (ignores current organization id)
 *   - type: required
 *   - bio: optional
 *   - website: optional, must be valid URL
 *   - status: optional (nullable) if user has role system-admin (values: active, notactive)
 *
 * Authorization:
 * - Currently allows all requests (authorize() returns true).
 * - Can be extended to restrict based on user roles or permissions.
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
        if ($this->isMethod('post')) {
            $rules = [
                'license_number' => 'required|string|max:255|unique:organizations,license_number',
                'type'           => 'required|string|max:100',
                'bio'            => 'nullable|string',
                'website'        => 'nullable|url|max:255',
                'user_id'        => 'required|exists:users,id',
            ];

            
            if ($this->user()->hasRole('system-admin')) {
                $rules['status'] = 'required|in:active,notactive';
            }

            return $rules;
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'license_number' => 'required|string|max:255|unique:organizations,license_number,' . $this->route('organization'),
                'type'           => 'required|string|max:100',
                'bio'            => 'nullable|string',
                'website'        => 'nullable|url|max:255',
            ];

            if ($this->user()->hasRole('system-admin')) {
                $rules['status'] = 'nullable|in:active,notactive';
            }

            return $rules;
        }

        return [];
    }
}
