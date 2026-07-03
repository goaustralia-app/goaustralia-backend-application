<?php

namespace App\Domains\InvitationRound\Repositories;

use App\Models\InvitationRound;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InvitationRoundRepository implements InvitationRoundRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $datesQuery = InvitationRound::query()
            ->select('round_date')
            ->distinct();

        if (isset($filters['subclass_id'])) {
            $datesQuery->where('subclass_id', $filters['subclass_id']);
        }

        if (isset($filters['from_date'])) {
            $datesQuery->whereDate('round_date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $datesQuery->whereDate('round_date', '<=', $filters['to_date']);
        }

        $datesPaginator = $datesQuery->orderByDesc('round_date')->paginate($perPage);

        $dates = $datesPaginator->pluck('round_date');

        $roundsQuery = InvitationRound::query()
            ->with('subclass')
            ->whereIn('round_date', $dates);

        if (isset($filters['subclass_id'])) {
            $roundsQuery->where('subclass_id', $filters['subclass_id']);
        }

        $roundsByDate = $roundsQuery->get()
            ->groupBy(fn (InvitationRound $round) => $round->round_date->toDateString());

        return $datesPaginator->through(fn ($item) => [
            'round_date' => $item->round_date->toDateString(),
            'rounds' => $roundsByDate[$item->round_date->toDateString()] ?? collect(),
        ]);
    }

    public function findById(int $id): ?array
    {
        $round = InvitationRound::find($id);

        if (! $round) {
            return null;
        }

        $rounds = InvitationRound::query()
            ->with(['subclass', 'details'])
            ->whereDate('round_date', $round->round_date)
            ->get();

        return [
            'round_date' => $round->round_date->toDateString(),
            'rounds' => $rounds,
        ];
    }
}
