<?php

namespace App\Domains\StatesSkilledOccupationList\Repositories;

use App\Models\StatesSkilledOccupationList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StatesSkilledOccupationListRepository implements StatesSkilledOccupationListRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 50): LengthAwarePaginator
    {
        $query = StatesSkilledOccupationList::query()->with(['subclass', 'stateRelation']);

        if (isset($filters['state_id'])) {
            $query->where('state_id', $filters['state_id']);
        }

        if (isset($filters['state'])) {
            $query->where('state', strtoupper($filters['state']));
        }

        if (isset($filters['subclass_id'])) {
            $query->where('subclass_id', $filters['subclass_id']);
        }

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['financial_year'])) {
            $query->where('financial_year', $filters['financial_year']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('occupation', 'like', "%{$search}%")
                    ->orWhere('anzsco_code', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('occupation')->paginate($perPage);
    }

    public function findById(int $id): ?StatesSkilledOccupationList
    {
        return StatesSkilledOccupationList::with(['subclass', 'stateRelation'])->find($id);
    }
}
