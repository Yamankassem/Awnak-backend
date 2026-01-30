<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterRequest
 *
 * Validates user registration data.
 *
 * Expected payload:
 * - name: string (required, max 255 characters)
 * - email: string (required, valid email, unique)
 * - password: string (required, min 8 characters, must be confirmed)
 *
 * Notes:
 * - The `password_confirmation` field is required implicitly
 *   by the `confirmed` validation rule.
 */
class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
