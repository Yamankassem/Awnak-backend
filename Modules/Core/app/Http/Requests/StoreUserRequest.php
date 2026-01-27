<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreUserRequest
 *
 * Validates user creation data.
 *
 * Expected payload:
 * - name: string (required)
 * - email: string (required, unique)
 * - password: string (required, min 8 characters)
 * - status: string (optional: active|inactive)
 *
 * Authorization:
 * - Requires `users.create` permission.
 */
class StoreUserRequest extends FormRequest
{
    /**
     * Define validation rules for creating a user.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'status'   => ['nullable', 'in:active,inactive'],
        ];
    }

     /**
     * Determine if the authenticated user is authorized
     * to create users.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('users.create');
    }
}
