<?php

namespace Modules\Volunteers\Http\Requests\VolunteerInterest;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class StoreVolunteerInterestRequest
 *
 * Validates input for attaching an interest
 * to the authenticated volunteer profile.
 *
 * @package Modules\Volunteers\Http\Requests\VolunteerInterest
 */
class StoreVolunteerInterestRequest extends FormRequest
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
     * Requires `profile.update.own` permission.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('profile.update.own');
    }
}
