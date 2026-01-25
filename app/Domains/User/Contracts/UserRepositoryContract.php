<?php

namespace App\Domains\User\Contracts;

use App\Models\User;

interface UserRepositoryContract
{
    public function create(array $data): User;

    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;

    public function update(User $user, array $data): bool;

    public function markEmailAsVerified(User $user): bool;
}
