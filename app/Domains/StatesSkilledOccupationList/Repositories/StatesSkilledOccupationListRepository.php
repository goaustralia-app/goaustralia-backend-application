<?php

namespace App\Domains\StatesSkilledOccupationList\Repositories;

use App\Models\State;
use App\Models\StatesSkilledOccupationList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StatesSkilledOccupationListRepository implements StatesSkilledOccupationListRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 50): LengthAwarePaginator
    {
        $query = State::query();

        if (isset($filters['state_id'])) {
            $query->where('id', $filters['state_id']);
        }

        if (isset($filters['state'])) {
            $query->where('code', strtoupper($filters['state']));
        }

        $query->with(['skilledOccupationLists' => function ($q) use ($filters) {
            $q->with('subclass');

            if (isset($filters['subclass_id'])) {
                $q->where('subclass_id', $filters['subclass_id']);
            }

            if (isset($filters['category'])) {
                $q->where('category', $filters['category']);
            }

            if (isset($filters['financial_year'])) {
                $q->where('financial_year', $filters['financial_year']);
            }

            if (isset($filters['search'])) {
                $search = $filters['search'];
                $q->where(function ($sq) use ($search) {
                    $sq->where('occupation', 'like', "%{$search}%")
                        ->orWhere('anzsco_code', 'like', "%{$search}%");
                });
            }

            $q->orderBy('occupation');
        }]);

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?StatesSkilledOccupationList
    {
        return StatesSkilledOccupationList::with(['subclass', 'stateRelation'])->find($id);
    }

    public function findStateWithOccupations(string $stateIdentifier, array $filters = []): ?State
    {
        $query = State::query();

        if (is_numeric($stateIdentifier)) {
            $query->where('id', $stateIdentifier);
        } else {
            $query->where('code', strtoupper($stateIdentifier));
        }

        return $query->with(['skilledOccupationLists' => function ($q) use ($filters) {
            $q->with('subclass');

            if (isset($filters['subclass_id'])) {
                $q->where('subclass_id', $filters['subclass_id']);
            }

            if (isset($filters['category'])) {
                $q->where('category', $filters['category']);
            }

            if (isset($filters['financial_year'])) {
                $q->where('financial_year', $filters['financial_year']);
            }

            $q->orderBy('occupation');
        }])->first();
    }
}
