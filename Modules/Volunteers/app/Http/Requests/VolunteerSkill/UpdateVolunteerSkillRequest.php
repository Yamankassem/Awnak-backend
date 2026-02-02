<?php

namespace Modules\Volunteers\Http\Requests\VolunteerSkill;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class UpdateVolunteerSkillRequest
 *
 * Validates input for updating the level
 * of an existing volunteer skill.
 *
 * @package Modules\Volunteers\Http\Requests\VolunteerSkill
 */
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
     * Authorization is handled via policy.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
