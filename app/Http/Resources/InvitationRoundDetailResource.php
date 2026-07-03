<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationRoundDetailResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'occupation' => $this->occupation,
            'anzsco_code' => $this->anzsco_code,
            'points' => $this->points,
            'eoi_count' => $this->eoi_count,
            'occupation_list' => $this->whenLoaded('occupationList', fn () => [
                'id' => $this->occupationList->id,
                'occupation' => $this->occupationList->occupation,
                'anzsco_code' => $this->occupationList->anzsco_code,
                'list' => $this->occupationList->list,
            ]),
        ];
    }
}
