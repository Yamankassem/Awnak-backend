<?php

namespace Modules\Evaluations\Http\Requests\VolunteerBadge;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVolunteerBadgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'badge_id' => 'sometimes|exists:badges,id',
        ];
    }
}
