<?php

namespace App\Domains\Country\Services;

use App\Domains\Country\Contracts\CountryRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class CountryService
{
    public function __construct(
        private CountryRepositoryContract $countryRepository
    ) {}

    public function getAllCountries(): Collection
    {
        return $this->countryRepository->getAll();
    }

    public function getCountryById(int $id): ?array
    {
        $country = $this->countryRepository->findById($id);

        if (! $country) {
            return null;
        }

        return [
            'id' => $country->id,
            'name' => $country->name,
            'code' => $country->code,
            'flag' => $country->flag,
        ];
    }
}
