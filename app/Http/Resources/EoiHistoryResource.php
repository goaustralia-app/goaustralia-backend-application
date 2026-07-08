<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EoiHistoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'change_type' => $this->change_type,
            'previous_value' => $this->previous_value,
            'updated_value' => $this->updated_value,
            'notes' => $this->notes,
            'changed_at' => $this->changed_at,
        ];
    }
}
