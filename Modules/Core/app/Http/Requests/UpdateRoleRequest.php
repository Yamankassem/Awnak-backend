<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRoleRequest
 *
 * Validates role update data.
 *
 * Expected payload (all optional):
 * - name: string (unique)
 * - permissions: array<string>
 *
 * Authorization:
 * - Requires `roles.update` permission.
 */
class UpdateRoleRequest extends FormRequest
{
    /**
     * Define validation rules for updating a role.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100', 'unique:roles,name,' . $this->route('id')],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    /**
     * Determine if the authenticated user is authorized
     * to update roles.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('roles.update');
    }
}
