<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRoleRequest
 *
 * Validates role creation data.
 *
 * Expected payload:
 * - name: string (required, unique)
 * - permissions: array<string> (optional)
 *
 * Authorization:
 * - Requires `roles.create` permission.
 */
class StoreRoleRequest extends FormRequest
{
    /**
     * Define validation rules for creating a role.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    /**
     * Determine if the authenticated user is authorized
     * to create roles.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('roles.create');
    }
}
