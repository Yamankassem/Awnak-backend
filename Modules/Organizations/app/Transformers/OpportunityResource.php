<?php

namespace Modules\Organizations\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organizations\Transformers\OrganizationResource as ResourcesOrganizationResource;

/**
 * Resource: OpportunityResource
 *
 * Transforms opportunity data into a structured JSON response.
 * Includes core attributes, related organization, and associated skills.
 *
 * Fields:
 * - id: Unique identifier of the opportunity
 * - title: Title of the opportunity
 * - description: Detailed description
 * - type: Type of opportunity (volunteering, training, job, etc.)
 * - start_date / end_date: Opportunity timeline
 * - status: Current status (approved, rejected, pending)
 * - organization: Related organization data (via OrganizationResource)
 * - skills: Associated skills (via OpportunitySkillResource)
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
        ];
    }
}
