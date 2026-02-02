<?php

namespace Modules\Volunteers\Http\Requests\VolunteerSkill;

use Illuminate\Foundation\Http\FormRequest;

class StoreVolunteerSkillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'skill_id' => ['required', 'exists:skills,id'],
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
