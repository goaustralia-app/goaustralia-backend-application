<?php

namespace App\Domains\OccupationList\Repositories;

use App\Models\OccupationList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OccupationListRepository implements OccupationListRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 50): LengthAwarePaginator
    {
        $query = OccupationList::query();

        if (isset($filters['list'])) {
            $query->where('list', $filters['list']);
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

    public function findById(int $id): ?OccupationList
    {
        return OccupationList::find($id);
    }

    public function findByFilters(array $filters): Collection
    {
        $query = OccupationList::query();

        if (isset($filters['list'])) {
            $query->where('list', $filters['list']);
        }

        if (isset($filters['anzsco_code'])) {
            $query->where('anzsco_code', $filters['anzsco_code']);
        }

        return $query->get();
    }

    public function search(string $query): Collection
    {
        return OccupationList::where('occupation', 'like', "%{$query}%")
            ->orWhere('anzsco_code', 'like', "%{$query}%")
            ->orderBy('occupation')
            ->get();
    }
}
