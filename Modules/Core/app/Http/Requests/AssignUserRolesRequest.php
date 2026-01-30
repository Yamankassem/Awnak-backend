<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AssignUserRolesRequest
 *
 * Validates and authorizes assigning roles to a user.
 *
 * Expected payload:
 * - roles: array<string> (required, at least one role)
 *
 * Authorization:
 * - Requires `roles.update` permission.
 */
class AssignUserRolesRequest extends FormRequest
{
    /**
     * Define validation rules for assigning roles.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    /**
     * Determine if the authenticated user is authorized
     * to assign roles.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('roles.update');
    }
}
