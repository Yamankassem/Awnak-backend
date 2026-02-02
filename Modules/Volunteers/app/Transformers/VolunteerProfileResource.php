<?php

namespace Modules\Volunteers\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // identity
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,

            // contact
            'phone' => $this->phone,
            'gender' => $this->gender,
            'birth_date' => optional($this->birth_date)?->toDateString(),

            // profile
            'bio' => $this->bio,
            'experience_years' => $this->experience_years,
            'previous_experience_details' => $this->previous_experience_details,

            // status
            'status' => $this->status,
            'verified_at' => $this->verified_at
                ? $this->verified_at->toDateTimeString()
                : null,

            // relations (only if loaded)
            'skills' => $this->whenLoaded('skills'),
            'interests' => $this->whenLoaded('interests'),
            'availability' => $this->whenLoaded('availability'),

            'location' => $this->whenLoaded('location', fn() => [
                'id' => $this->location?->id,
                'name' => $this->location?->name,
            ]),

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
