<?php

namespace Modules\Volunteers\Http\Requests\Interests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateInterestRequest
 *
 * Handles validation and authorization for updating interests.
 *
 * @package Modules\Volunteers\Http\Requests\Interests
 */
class UpdateInterestRequest extends FormRequest
{
    /**
     * Validation rules for updating an interest.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
         return [
            'name' => ['sometimes', 'string', 'max:100'],
        ];
    }

    /**
     * Authorization logic for the request.
     *
     * @return bool True if user can update interests
     */
    public function authorize(): bool
    {
        return $this->user()->can('interests.update');
    }
}
