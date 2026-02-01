<?php

namespace App\Domains\VisaSubclass\Repositories;

use App\Models\VisaSubclass;
use Illuminate\Database\Eloquent\Collection;

interface VisaSubclassRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 50): Collection;

    public function findById(int $id): ?VisaSubclass;

    public function findBySubclassCode(string $subclassCode): ?VisaSubclass;

    public function findByFilters(array $filters): Collection;

    public function search(string $query): Collection;
}
