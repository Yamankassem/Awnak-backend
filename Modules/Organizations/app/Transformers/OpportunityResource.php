<?php

namespace Modules\Organizations\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organizations\Transformers\OrganizationResource as ResourcesOrganizationResource;

/**
 * Resource: OpportunityResource
 *
 * Transforms opportunity data into a structured JSON response.
 */
class OpportunityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'type'         => $this->type,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date,

            'organization' => $this->when(
                $this->relationLoaded('organization'),
                fn () => new ResourcesOrganizationResource($this->organization)
            ),

            'created_at'   => optional($this->created_at)->toDateTimeString(),
            'updated_at'   => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
