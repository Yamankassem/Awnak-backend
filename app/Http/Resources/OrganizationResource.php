<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /** * Transform the resource into an array. *
     *
     * This resource formats the Organization model data
     * * into a clean JSON response for API consumers. *
     *  * Fields included: *
     *  - id: Primary key *
     * - license_number: Unique license number of the organization *
     *  - type: Type of organization (e.g., NGO, school, charity) *
     * - bio: Short description or background *
     *  - website: Official website (optional) *
     * - created_at: Date when the organization was created *
     *  - updated_at: Date when the organization was last updated */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'license_number' => $this->license_number,
            'type' => $this->type,
            'bio' => $this->bio,
            'website' => $this->website,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        //لعرض المتطوعين الخاصين بالمنظمة     'volunteers' => VolunteerResource::collection($this->whenLoaded('volunteers')),

        ];
    }
}
