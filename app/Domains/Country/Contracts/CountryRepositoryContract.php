<?php

namespace App\Domains\Country\Contracts;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

interface CountryRepositoryContract
{
    public function getAll(): Collection;

    public function findById(int $id): ?Country;

    public function findByCode(string $code): ?Country;
}
