<?php

namespace Modules\Volunteers\Http\Requests\VolunteerInterest;

use Illuminate\Foundation\Http\FormRequest;

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
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('profile.update.own');
    }
}
