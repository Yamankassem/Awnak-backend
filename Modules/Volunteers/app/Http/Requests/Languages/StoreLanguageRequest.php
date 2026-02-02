<?php

namespace Modules\Volunteers\Http\Requests\Languages;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    /**
     * Validation rules for storing a new language.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:10', 'unique:languages,code'],
        ];
    }

    /**
     * Authorization logic for the request.
     *
     * @return bool True if user can create languages
     */
    public function authorize(): bool
    {
        return $this->user()->can('languages.create');
    }
}
