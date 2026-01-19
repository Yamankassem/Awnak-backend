<?php

namespace Modules\Organizations\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource: DocumentResource
 *
 * Transforms document data into a structured JSON response.
 */
class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'file_path'      => $this->file_path,
            'file_type'      => $this->file_type,
            'file_size'      => $this->file_size,
            'opportunity_id' => $this->opportunity_id,
            'created_at'     => optional($this->created_at)->toDateTimeString(),
            'updated_at'     => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
