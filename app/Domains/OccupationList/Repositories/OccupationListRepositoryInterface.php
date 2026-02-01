<?php

namespace App\Domains\OccupationList\Repositories;

use App\Models\OccupationList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface OccupationListRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 50): LengthAwarePaginator;

    public function findById(int $id): ?OccupationList;

    public function findByFilters(array $filters): Collection;

    public function search(string $query): Collection;
}
