<?php

namespace Modules\Volunteers\Http\Requests\Interests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterestRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
         return [
            'name' => ['required', 'string', 'max:100', 'unique:interests,name'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('interests.create');
    }
}
