<?php

namespace Modules\Volunteers\Http\Requests\Skills;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class StoreSkillRequest
 *
 * Validates input for creating a new skill.
 * Restricted to users with `skills.create` permission.
 *
 * @package Modules\Volunteers\Http\Requests\Skills
 */
class StoreSkillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
       return [
            'name' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Authorization check based on permission.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('skills.create');
    }
}
