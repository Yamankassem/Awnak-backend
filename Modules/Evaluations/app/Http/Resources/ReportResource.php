<?php

namespace Modules\Evaluations\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'report_type' => $this->report_type,
            'param' => $this->param,
            'url' => $this->url,
            'generated_by' => $this->generated_by,
            'generated_at' => $this->generated_at,
            'created_at' => $this->created_at,
        ];
    }
}
