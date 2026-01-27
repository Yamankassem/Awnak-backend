<?php

namespace Modules\Evaluations\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'task' => [
                'id' => $this->task_id,
            ],

            'hours' => $this->hours,
            'context' => $this->context,
            'issued_at' => $this->issued_at,

            'created_at' => $this->created_at,
        ];
    }
}
