<?php

namespace App\Domains\StatesSkilledOccupationList\Repositories;

use App\Models\StatesSkilledOccupationList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StatesSkilledOccupationListRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 50): LengthAwarePaginator;

    public function findById(int $id): ?StatesSkilledOccupationList;
}
