<?php

namespace Modules\Evaluations\Http\Requests\VolunteerBadge;

use Illuminate\Foundation\Http\FormRequest;

class StoreVolunteerBadgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'volunteer_id' => 'required|exists:volunteers,id',
            'badge_id'     => 'required|exists:badges,id',
        ];
    }
}
