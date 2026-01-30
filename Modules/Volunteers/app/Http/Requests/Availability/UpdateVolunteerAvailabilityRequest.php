<?php

namespace Modules\Volunteers\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVolunteerAvailabilityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
         return [
            'day' => ['sometimes', 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'date_format:H:i', 'after:start_time'],
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
