<?php

namespace Modules\Volunteers\Http\Requests\Languages;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageRequest extends FormRequest
{
    /**
     * Validation rules for updating a language.
     *
     * Ensures language code remains unique.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
         return [
            'name' => ['sometimes', 'string', 'max:100'],
            'code' => [
                'sometimes',
                'string',
                'max:10',
                Rule::unique('languages', 'code')->ignore($this->language),
            ],
        ];
    }

    /**
     * Authorization logic for the request.
     *
     * @return bool True if user can update languages
     */
    public function authorize(): bool
    {
        return $this->user()->can('languages.update');
    }
}
