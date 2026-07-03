<?php

namespace App\Http\Resources;

use App\Models\InvitationRound;
use App\Models\InvitationRoundDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationRoundShowResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \Illuminate\Support\Collection<int, InvitationRound> $rounds */
        $rounds = $this->resource['rounds'];

        $subclasses = $rounds->map(fn (InvitationRound $round) => [
            'id' => $round->id,
            'number_of_invitations' => $round->number_of_invitations,
            'number_of_applications' => $round->number_of_applications,
            'minimum_points' => $round->minimum_points,
            'tie_break_date' => $round->tie_break_date?->toDateTimeString(),
            'subclass' => $round->subclass ? [
                'id' => $round->subclass->id,
                'subclass_code' => $round->subclass->subclass_code,
                'name' => $round->subclass->name,
            ] : null,
        ])->values()->all();

        $totalInvitations = $rounds->sum('number_of_invitations');

        $occupations = collect();

        foreach ($rounds as $round) {
            $subclassCode = $round->subclass?->subclass_code;

            foreach ($round->details as $detail) {
                /** @var InvitationRoundDetail $detail */
                $key = $detail->anzsco_code ?? $detail->occupation;

                if (! $occupations->has($key)) {
                    $occupations->put($key, [
                        'occupation' => $detail->occupation,
                        'anzsco_code' => $detail->anzsco_code,
                        'subclasses' => [],
                    ]);
                }

                $entry = $occupations->get($key);
                $entry['subclasses'][] = [
                    'subclass_code' => $subclassCode,
                    'points' => $detail->points,
                ];
                $occupations->put($key, $entry);
            }
        }

        return [
            'round_date' => $this->resource['round_date'],
            'total_invitations' => $totalInvitations,
            'subclasses' => $subclasses,
            'occupations' => $occupations->values()->all(),
        ];
    }
}
