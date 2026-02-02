<?php

namespace Modules\Volunteers\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreVolunteerAvailabilityRequest
 *
 * Validates input for creating a new availability slot
 * for the authenticated volunteer.
 *
 * @package Modules\Volunteers\Http\Requests\Availability
 */
class StoreVolunteerAvailabilityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
         return [
            'day' => ['required', 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }

    /**
     * Authorization check for creating availability.
     *
     * Currently allowed for authenticated users.
     * Authorization is enforced at controller/policy level.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
