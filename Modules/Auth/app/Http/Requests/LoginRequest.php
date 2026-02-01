<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 *
 * Validates login credentials for authentication.
 *
 * Expected payload:
 * - email: string (required, valid email address)
 * - password: string (required)
 *
 * Authorization:
 * - Always allowed (authorization handled at controller/service level)
 */
class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
         return [
            'email' => ['required','email'],
            'password' => ['required','string'],
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
