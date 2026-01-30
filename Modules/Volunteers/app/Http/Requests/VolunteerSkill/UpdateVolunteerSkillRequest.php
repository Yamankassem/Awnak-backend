<?php

namespace Modules\Volunteers\Http\Requests\VolunteerSkill;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVolunteerSkillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
         return [
            'level' => ['required', 'in:beginner,intermediate,advanced,expert'],
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
