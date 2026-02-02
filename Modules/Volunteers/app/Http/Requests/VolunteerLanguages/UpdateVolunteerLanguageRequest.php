<?php

namespace Modules\Volunteers\Http\Requests\VolunteerLanguages;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVolunteerLanguageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'level' => ['required', 'in:basic,intermediate,fluent,native'],
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
