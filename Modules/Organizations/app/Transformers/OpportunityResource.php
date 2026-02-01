<?php

namespace Modules\Organizations\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organizations\Transformers\OrganizationResource as ResourcesOrganizationResource;

/**
 * Resource: OpportunityResource
 *
 * Transforms Opportunity model data into a structured JSON response.
 * Includes core attributes, related organization, location, and skills.
 *
 * Input:
 * - Opportunity model (with relationships loaded: organization, location, skills)
 *
 * Output (JSON):
 * - id: Unique identifier
 * - title: Opportunity title
 * - description: Detailed description
 * - type: Type of opportunity (volunteering, training, job, etc.)
 * - start_date / end_date: Timeline
 * - status: Current status (approved, rejected, pending)
 * - organization: Related organization data (via OrganizationResource)
 * - skills: Associated skills (via OpportunitySkillResource)
 * - location: { id, lat, lng, name, type }
 */

class OpportunityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'type'          => $this->type,
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'status'        => $this->status,
            'organization'  => new OrganizationResource($this->whenLoaded('organization')),
            'skills'        => OpportunitySkillResource::collection($this->whenLoaded('skills')),
            'location' => $this->location
                ? [
                    'id' => $this->location->id,
                    'lat' => $this->location->coordinates->latitude,
                    'lng' => $this->location->coordinates->longitude,
                    'name' => $this->location->name ?? null,
                    'type' => $this->location->type ?? null,
                ]
                : null,

        ];
    }
}
