<?php

namespace App\Http\Resources;

use App\Models\InvitationRound;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationRoundGroupedResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'round_date' => $this->resource['round_date'],
            'subclasses' => $this->resource['rounds']->map(fn (InvitationRound $round) => [
                'id' => $round->id,
                'number_of_invitations' => $round->number_of_invitations,
                'minimum_points' => $round->minimum_points,
                'tie_break_date' => $round->tie_break_date?->toDateTimeString(),
                'subclass' => $round->subclass ? [
                    'id' => $round->subclass->id,
                    'subclass_code' => $round->subclass->subclass_code,
                    'name' => $round->subclass->name,
                ] : null,
            ])->values()->all(),
        ];
    }
}
