<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationRoundResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'round_date' => $this->round_date?->toDateString(),
            'number_of_invitations' => $this->number_of_invitations,
            'number_of_applications' => $this->number_of_applications,
            'minimum_points' => $this->minimum_points,
            'tie_break_date' => $this->tie_break_date?->toDateTimeString(),
            'subclass' => $this->whenLoaded('subclass', fn () => [
                'id' => $this->subclass->id,
                'subclass_code' => $this->subclass->subclass_code,
                'name' => $this->subclass->name,
            ]),
            'details' => InvitationRoundDetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
