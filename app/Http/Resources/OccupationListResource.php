<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OccupationListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'occupation' => $this->occupation,
            'anzsco_code' => $this->anzsco_code,
            'assessing_authority' => $this->assessing_authority,
            'list' => $this->list,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
