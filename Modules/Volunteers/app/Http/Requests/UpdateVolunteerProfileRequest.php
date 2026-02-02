<?php

namespace Modules\Volunteers\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
/**
 * Class UpdateVolunteerProfileRequest
 *
 * Validates updates to volunteer profile information.
 *
 * @package Modules\Volunteers\Http\Requests
 */
class UpdateVolunteerProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
         return [
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'phone' => ['sometimes', 'string', 'max:20'],
            'gender' => ['sometimes', 'in:male,female,other'],
            'birth_date' => ['sometimes', 'date'],
            'bio' => ['sometimes', 'string'],
            'experience_years' => ['sometimes', 'integer', 'min:0'],
            'previous_experience_details' => ['sometimes', 'string'],
        ];
    }
    /**
     * Authorization is enforced via policy.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
