<?php

namespace Modules\Organizations\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource: OrganizationResource
 *
 * Transforms Organization model data into a structured JSON response.
 * Provides core attributes and metadata for API consumers.
 *
 * Input:
 * - Organization model (with optional relationships loaded)
 *
 * Output (JSON):
 * - id: Unique identifier of the organization
 * - license_number: License number string
 * - type: Type of organization (e.g., NGO, foundation, charity)
 * - bio: Background or description text
 * - website: Organization website URL
 * - created_at: Timestamp of creation (ISO string)
 * - updated_at: Timestamp of last update (ISO string)
 * - status: Current status (active, notactive, pending, etc.)
 * - volunteers: Collection of VolunteerResource (only if relationship is loaded)
 *
 * Example usage:
 * return new OrganizationResource($organization->load('volunteers'));
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
            'status'         => $this->status,
            // Show volunteers related to the organization (only if loaded)
            //    'volunteers'     => VolunteerResource::collection($this->whenLoaded('volunteers')),
        ];
    }
}
