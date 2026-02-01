<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserRequest
 *
 * Validates user update data.
 *
 * Expected payload (all optional):
 * - name: string
 * - email: string (unique)
 * - password: string (min 8 characters)
 * - status: string (active|inactive)
 *
 * Authorization:
 * - Requires `users.update` permission.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Define validation rules for updating a user.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string', 'max:255'],
            'email'    => ['sometimes', 'email', 'unique:users,email,' . $this->route('id')],
            'password' => ['sometimes', 'string', 'min:8'],
            'status'   => ['sometimes', 'in:active,inactive'],
        ];
    }

    /**
     * Determine if the authenticated user is authorized
     * to update users.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('users.update');
    }
}
