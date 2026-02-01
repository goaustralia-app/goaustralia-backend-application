<?php

namespace App\Domains\VisaSubclass\Repositories;

use App\Models\VisaSubclass;
use Illuminate\Database\Eloquent\Collection;

class VisaSubclassRepository implements VisaSubclassRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 50): Collection
    {
        $query = VisaSubclass::query()->where('status', 1);

        if (isset($filters['is_permanent'])) {
            $query->where('is_permanent', $filters['is_permanent']);
        }

        if (isset($filters['points_tested'])) {
            $query->where('points_tested', $filters['points_tested']);
        }

        if (isset($filters['stream'])) {
            $query->where('stream', $filters['stream']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('subclass_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('subclass_code')->get();
    }

    public function findById(int $id): ?VisaSubclass
    {
        return VisaSubclass::find($id);
    }

    public function findBySubclassCode(string $subclassCode): ?VisaSubclass
    {
        return VisaSubclass::where('subclass_code', $subclassCode)->first();
    }

    public function findByFilters(array $filters): Collection
    {
        $query = VisaSubclass::query()->where('status', 1);

        if (isset($filters['is_permanent'])) {
            $query->where('is_permanent', $filters['is_permanent']);
        }

        if (isset($filters['points_tested'])) {
            $query->where('points_tested', $filters['points_tested']);
        }

        if (isset($filters['stream'])) {
            $query->where('stream', $filters['stream']);
        }

        return $query->get();
    }

    public function search(string $query): Collection
    {
        return VisaSubclass::where('status', 1)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('subclass_code', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('subclass_code')
            ->get();
    }
}
