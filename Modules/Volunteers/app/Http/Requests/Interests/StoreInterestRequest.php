<?php

namespace Modules\Volunteers\Http\Requests\Interests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreInterestRequest
 *
 * Handles validation and authorization for creating interests.
 *
 * @package Modules\Volunteers\Http\Requests\Interests
 */
class StoreInterestRequest extends FormRequest
{
    /**
     * Validation rules for storing a new interest.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:interests,name'],
        ];
    }

    /**
     * Authorization logic for the request.
     *
     * @return bool True if user can create interests
     */
    public function authorize(): bool
    {
        return $this->user()->can('interests.create');
    }
}
