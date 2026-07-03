<?php

namespace App\Domains\InvitationRound\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InvitationRoundRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    /** @return array{round_date: string, rounds: \Illuminate\Support\Collection}|null */
    public function findById(int $id): ?array;
}
