<?php

namespace Modules\Volunteers\Http\Requests\Skills;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class UpdateSkillRequest
 *
 * Validates input for updating an existing skill.
 *
 * @package Modules\Volunteers\Http\Requests\Skills
 */
class UpdateSkillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100'],
        ];
    }

    /**
     * Authorization check based on permission.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('skills.update');
    }
}
