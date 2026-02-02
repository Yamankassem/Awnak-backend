<?php

namespace Modules\Volunteers\Http\Requests\VolunteerSkill;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class StoreVolunteerSkillRequest
 *
 * Validates input for attaching a skill
 * to the volunteer profile with a proficiency level.
 *
 * @package Modules\Volunteers\Http\Requests\VolunteerSkill
 */
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
     * Authorization is handled via policy.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
