<?php

namespace Modules\Volunteers\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

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
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
