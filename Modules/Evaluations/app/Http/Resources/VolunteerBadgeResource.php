<?php

namespace Modules\Evaluations\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerBadgeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'volunteer' => [
                'id' => $this->volunteer_id,
            ],

            'badge' => [
                'id' => $this->badge_id,
            ],

            'awarded_by' => $this->awarded_by,
            'awarded_at' => $this->awarded_at,

            'created_at' => $this->created_at,
        ];
    }
}
