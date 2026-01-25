<?php

namespace App\Domains\Country\Repositories;

use App\Domains\Country\Contracts\CountryRepositoryContract;
use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

class CountryRepository implements CountryRepositoryContract
{
    public function getAll(): Collection
    {
        return Country::all();
    }

    public function findById(int $id): ?Country
    {
        return Country::find($id);
    }

    public function findByCode(string $code): ?Country
    {
        return Country::where('code', $code)->first();
    }
}
