<?php

namespace Modules\Organizations\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

# use Modules\Organizations\Transformers\VolunteerResource;

/**
 * Resource: OrganizationResource
 *
 * Formats organization data into a clean JSON response for API consumers.
 */
class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'license_number' => $this->license_number,
            'type'           => $this->type,
            'bio'            => $this->bio,
            'website'        => $this->website,
            'created_at'     => optional($this->created_at)->toDateTimeString(),
            'updated_at'     => optional($this->updated_at)->toDateTimeString(),

            // Show volunteers related to the organization (only if loaded)
        //    'volunteers'     => VolunteerResource::collection($this->whenLoaded('volunteers')),
        ];
    }
}
