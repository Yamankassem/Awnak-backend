<?php

namespace Modules\Volunteers\Http\Requests\VolunteerInterest;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class UpdateVolunteerInterestRequest
 *
 * Validates input for updating a volunteer interest.
 *
 * @package Modules\Volunteers\Http\Requests\VolunteerInterest
 */
class UpdateVolunteerInterestRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'interest_id' => ['required', 'exists:interests,id'],
        ];
    }

    /**
     * Authorization check.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('profile.update.own');
    }
}
